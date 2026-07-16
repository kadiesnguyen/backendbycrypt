<?php

namespace App\Services;

use App\Models\Bill;
use App\Models\Hyorder;
use App\Models\HyResultQueue;
use App\Models\User;
use App\Models\UserCoin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Support\TradingSymbol;

class HyorderSettlementService
{
    private const YAHOO_SYMBOL_MAP = [
        'XAUUSD' => 'GC=F',
        'XAGUSD' => 'SI=F',
        'GBPUSD' => 'GBPUSD=X',
        'USDJPY' => 'USDJPY=X',
        'EURUSD' => 'EURUSD=X',
        'AAPL' => 'AAPL',
    ];

    /**
     * Settle all overdue pending orders (cron / admin batch).
     *
     * @return array{settled: int, failed: int, due: int}
     */
    public function settleDueOrders(int $maxOrders = 500): array
    {
        $currentTime = now()->timestamp + 10;

        $orders = Hyorder::query()
            ->where('status', 1)
            ->where('intselltime', '<=', $currentTime)
            ->orderBy('buytime')
            ->orderBy('id')
            ->limit($maxOrders)
            ->get();

        $settled = 0;
        $failed = 0;

        foreach ($orders as $order) {
            try {
                $this->settle($order);
                $settled++;
            } catch (\Throwable $e) {
                $failed++;
                Log::error('Failed to auto-settle hyorder', [
                    'id' => $order->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return [
            'settled' => $settled,
            'failed' => $failed,
            'due' => $orders->count(),
        ];
    }

    /**
     * Count pending orders past settlement time.
     */
    public function countOverdue(): int
    {
        return Hyorder::query()
            ->where('status', 1)
            ->where('intselltime', '<=', now()->timestamp + 10)
            ->count();
    }

    /**
     * Settle a pending contract order (ThinkPHP Trade/manualApprove + ProcessHyOrders).
     */
    public function settle(Hyorder $order): void
    {
        if ((int) $order->status !== 1) {
            throw new \RuntimeException('Order is not pending settlement.');
        }

        DB::transaction(function () use ($order) {
            $locked = Hyorder::query()->whereKey($order->id)->lockForUpdate()->first();

            if (!$locked || (int) $locked->status !== 1) {
                throw new \RuntimeException('Order is not pending settlement.');
            }

            $profitAmount = ContractSettlementMath::profitAmount($locked);
            $sellPrice = $this->fetchSellPrice((string) $locked->coinname);
            $winPayout = ContractSettlementMath::winPayout($locked);

            $updateData = [
                'status' => 2,
                'sellprice' => $sellPrice,
            ];

            $kongyk = (int) ($locked->kongyk ?? 0);

            if ($kongyk === 1) {
                $this->applyWin($locked, $profitAmount, $winPayout, $updateData);
            } elseif ($kongyk === 2) {
                $this->applyLoss($locked, $updateData);
            } else {
                $user = User::query()->find($locked->uid, ['hy_result_mode']);
                $resultMode = (int) ($user->hy_result_mode ?? 0);

                if ($resultMode === 1) {
                    $this->applyWin($locked, $profitAmount, $winPayout, $updateData);
                } elseif ($resultMode === 2) {
                    $this->applyLoss($locked, $updateData);
                } else {
                    $queueResult = HyResultQueue::query()
                        ->orderBy('round_no')
                        ->orderBy('id')
                        ->value('result');

                    if ($queueResult === 'WIN') {
                        $this->applyWin($locked, $profitAmount, $winPayout, $updateData);
                    } elseif ($queueResult === 'LOSS') {
                        $this->applyLoss($locked, $updateData);
                    } else {
                        $this->applyMarketResult($locked, $sellPrice, $profitAmount, $winPayout, $updateData);
                    }
                }
            }

            $locked->update($updateData);

            Log::info('Manually settled hyorder', [
                'id' => $locked->id,
                'sellprice' => $sellPrice,
                'is_win' => $updateData['is_win'] ?? null,
                'ploss' => $updateData['ploss'] ?? $locked->ploss,
            ]);
        });
    }

    /**
     * @param  array<string, mixed>  $updateData
     */
    protected function applyWin(
        Hyorder $order,
        float $profitAmount,
        float $winPayout,
        array &$updateData,
    ): void {
        $updateData['is_win'] = 1;
        $updateData['ploss'] = $profitAmount;
        $this->addUserBalance(
            (int) $order->uid,
            $winPayout,
            ContractSettlementMath::winRemark((int) $order->id),
        );
    }

    /**
     * @param  array<string, mixed>  $updateData
     */
    protected function applyLoss(Hyorder $order, array &$updateData): void
    {
        $lossAmount = ContractSettlementMath::profitAmount($order);
        $refundAmount = ContractSettlementMath::lossRefund($order);
        $updateData['is_win'] = 2;
        $updateData['ploss'] = $lossAmount;
        $this->addUserBalance(
            (int) $order->uid,
            $refundAmount,
            ContractSettlementMath::lossRemark((int) $order->id),
        );
    }

    /**
     * @param  array<string, mixed>  $updateData
     */
    protected function applyMarketResult(
        Hyorder $order,
        float|string $sellPrice,
        float $profitAmount,
        float $winPayout,
        array &$updateData,
    ): void {
        $sell = (float) $sellPrice;

        if ($sell > 0) {
            if ((int) $order->hyzd === 1) {
                if ($sell > (float) $order->buyprice) {
                    $this->applyWin($order, $profitAmount, $winPayout, $updateData);
                } else {
                    $this->applyLoss($order, $updateData);
                }
            } elseif ((int) $order->hyzd === 2) {
                if ($sell < (float) $order->buyprice) {
                    $this->applyWin($order, $profitAmount, $winPayout, $updateData);
                } else {
                    $this->applyLoss($order, $updateData);
                }
            }
        } else {
            $this->applyLoss($order, $updateData);
        }
    }

    protected function fetchSellPrice(string $coinname): float|string
    {
        $normalized = TradingSymbol::normalize($coinname);

        try {
            if (str_ends_with($normalized, 'USDT')) {
                $response = Http::timeout(5)->get(
                    "https://api.binance.com/api/v3/ticker/24hr?symbol={$normalized}",
                );

                if ($response->ok() && isset($response['lastPrice'])) {
                    return $response['lastPrice'];
                }
            }

            $yahooSymbol = self::YAHOO_SYMBOL_MAP[$normalized] ?? $normalized;
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
            ])->timeout(8)->get("https://query1.finance.yahoo.com/v8/finance/chart/{$yahooSymbol}");

            if ($response->ok()) {
                $meta = $response->json('chart.result.0.meta');
                if ($meta && isset($meta['regularMarketPrice'])) {
                    return $meta['regularMarketPrice'];
                }
            }

            $coinApi = "https://www.okx.com/api/v5/market/history-index-candles?instId={$normalized}";
            $okx = Http::timeout(5)->get($coinApi);

            if (!$okx->failed() && isset($okx['data'][0][4])) {
                return $okx['data'][0][4];
            }
        } catch (\Throwable $e) {
            Log::warning('Failed to fetch sell price for settle', [
                'coinname' => $coinname,
                'error' => $e->getMessage(),
            ]);
        }

        return 0;
    }

    protected function addUserBalance(int $userId, float $amount, string $remark = 'Trade win bonus'): void
    {
        if ($amount <= 0) {
            return;
        }

        if (Bill::query()->where('uid', $userId)->where('remark', $remark)->exists()) {
            return;
        }

        $userCoin = UserCoin::query()->where('userid', $userId)->lockForUpdate()->first();

        if (!$userCoin) {
            throw new \RuntimeException("User wallet not found for user {$userId}.");
        }

        $before = (float) $userCoin->usdt;
        $after = $before + $amount;
        $userCoin->usdt = number_format($after, 10, '.', '');

        if (!$userCoin->save()) {
            throw new \RuntimeException("Failed to credit user {$userId} wallet.");
        }

        $verified = (float) UserCoin::query()->where('userid', $userId)->value('usdt');

        if ($verified + 0.0001 < $after) {
            throw new \RuntimeException("Wallet credit verification failed for user {$userId}.");
        }

        $user = User::query()->find($userId, ['id', 'username']);

        if (!$user) {
            throw new \RuntimeException("User not found: {$userId}.");
        }

        Bill::query()->create([
            'uid' => $user->id,
            'username' => $user->username,
            'num' => $amount,
            'coinname' => 'usdt',
            'afternum' => $verified,
            'type' => 4,
            'addtime' => now()->format('Y-m-d H:i:s'),
            'st' => 1,
            'remark' => $remark,
        ]);
    }
}
