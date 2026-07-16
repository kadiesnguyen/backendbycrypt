<?php

namespace App\Console\Commands;

use App\Models\Bborder;
use App\Models\Bill;
use App\Models\UserCoin;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProcessLimitTradeOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:process-limit-trade-orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process open limit trade orders and fill when market price reaches target.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $openOrders = Bborder::where('ordertype', 1)
                ->where('status', 1)
                ->orderBy('id', 'asc')
                ->limit(500)
                ->get();

            if ($openOrders->isEmpty()) {
                $this->info('No open limit orders found.');
                return 0;
            }

            $filledCount = 0;
            $priceCache = [];

            foreach ($openOrders as $order) {
                $coin = strtolower(trim((string) $order->coin));
                if ($coin === '' || $coin === 'usdt') {
                    continue;
                }

                if (!array_key_exists($coin, $priceCache)) {
                    $priceCache[$coin] = $this->fetchTickerPrice($coin);
                }

                $marketPrice = $priceCache[$coin];
                if (!$marketPrice || $marketPrice <= 0) {
                    continue;
                }

                $limitPrice = (float) $order->price;
                $isBuy = (int) $order->type === 1;

                $canFill = $isBuy
                    ? $marketPrice <= $limitPrice
                    : $marketPrice >= $limitPrice;

                if (!$canFill) {
                    continue;
                }

                DB::beginTransaction();

                try {
                    $freshOrder = Bborder::where('id', $order->id)
                        ->where('status', 1)
                        ->lockForUpdate()
                        ->first();

                    if (!$freshOrder) {
                        DB::rollBack();
                        continue;
                    }

                    $userCoin = UserCoin::where('userid', $freshOrder->uid)
                        ->lockForUpdate()
                        ->first();

                    if (!$userCoin) {
                        DB::rollBack();
                        continue;
                    }

                    $coinColumn = $coin;
                    $coinFrozenColumn = $coin . '_d';
                    $coinAmount = (float) $freshOrder->coinnum;
                    $feeRate = (float) ($freshOrder->sxfbl ?? 0);

                    if ($isBuy) {
                        $frozenUsdt = (float) $freshOrder->usdtnum;
                        if ((float) ($userCoin->usdt_d ?? 0) < $frozenUsdt) {
                            DB::rollBack();
                            continue;
                        }

                        $actualUsdtCost = $coinAmount * $marketPrice;
                        $feeCoin = $feeRate > 0 ? ($coinAmount * $feeRate / 100) : 0;
                        $netCoin = max($coinAmount - $feeCoin, 0);
                        $refundUsdt = max($frozenUsdt - $actualUsdtCost, 0);

                        UserCoin::where('userid', $freshOrder->uid)->decrement('usdt_d', $frozenUsdt);

                        if ($refundUsdt > 0) {
                            UserCoin::where('userid', $freshOrder->uid)->increment('usdt', $refundUsdt);
                        }

                        UserCoin::where('userid', $freshOrder->uid)->increment($coinColumn, $netCoin);

                        $freshAfter = UserCoin::where('userid', $freshOrder->uid)->first();

                        Bill::create([
                            'uid' => $freshOrder->uid,
                            'username' => $freshOrder->account,
                            'num' => $netCoin,
                            'coinname' => $coinColumn,
                            'afternum' => $freshAfter->$coinColumn,
                            'type' => 13,
                            'addtime' => now()->format('Y-m-d H:i:s'),
                            'st' => 1,
                            'remark' => 'Spot limit buy filled',
                        ]);

                        if ($refundUsdt > 0) {
                            Bill::create([
                                'uid' => $freshOrder->uid,
                                'username' => $freshOrder->account,
                                'num' => $refundUsdt,
                                'coinname' => 'usdt',
                                'afternum' => $freshAfter->usdt,
                                'type' => 13,
                                'addtime' => now()->format('Y-m-d H:i:s'),
                                'st' => 1,
                                'remark' => 'Spot limit buy refund',
                            ]);
                        }

                        $freshOrder->update([
                            'status' => 2,
                            'xjprice' => $marketPrice,
                            'fee' => $feeCoin,
                            'tradetime' => now()->format('Y-m-d H:i:s'),
                        ]);
                    } else {
                        if ((float) ($userCoin->$coinFrozenColumn ?? 0) < $coinAmount) {
                            DB::rollBack();
                            continue;
                        }

                        $grossUsdt = $coinAmount * $marketPrice;
                        $feeUsdt = $feeRate > 0 ? ($grossUsdt * $feeRate / 100) : 0;
                        $netUsdt = max($grossUsdt - $feeUsdt, 0);

                        UserCoin::where('userid', $freshOrder->uid)->decrement($coinFrozenColumn, $coinAmount);
                        UserCoin::where('userid', $freshOrder->uid)->increment('usdt', $netUsdt);

                        $freshAfter = UserCoin::where('userid', $freshOrder->uid)->first();

                        Bill::create([
                            'uid' => $freshOrder->uid,
                            'username' => $freshOrder->account,
                            'num' => $netUsdt,
                            'coinname' => 'usdt',
                            'afternum' => $freshAfter->usdt,
                            'type' => 13,
                            'addtime' => now()->format('Y-m-d H:i:s'),
                            'st' => 1,
                            'remark' => 'Spot limit sell filled',
                        ]);

                        $freshOrder->update([
                            'status' => 2,
                            'xjprice' => $marketPrice,
                            'fee' => $feeUsdt,
                            'usdtnum' => $netUsdt,
                            'tradetime' => now()->format('Y-m-d H:i:s'),
                        ]);
                    }

                    DB::commit();
                    $filledCount++;
                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error('Failed to process limit order', [
                        'order_id' => $order->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
            $this->info('Processed limit orders, filled: ' . $filledCount);
            return 0;
        } catch (\Exception $e) {
            Log::error('Process limit trade orders failed', ['error' => $e->getMessage()]);
            $this->error('Process failed: ' . $e->getMessage());
            return 1;
        }
    }

    private function fetchTickerPrice(string $coinName)
    {
        $tickerUrlTemplate = config('services.binance.ticker_url');

        if (!$tickerUrlTemplate) {
            return null;
        }

        $symbol = strtoupper($coinName);
        $url = str_replace('{symbol}', $symbol, $tickerUrlTemplate);
        $response = Http::timeout(10)->get($url);

        if (!$response->ok()) {
            return null;
        }

        $price = (float) $response->json('price');

        return $price > 0 ? $price : null;
    }
}
