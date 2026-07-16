<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bborder;
use App\Models\Bill;
use App\Models\Coin;
use App\Models\UserCoin;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class TradeController extends Controller
{
    public function placeOrder(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'coin' => 'required|integer|exists:tw_coin,id',
                'side' => 'required|string|in:buy,sell',
                'order_type' => 'required|string|in:market,limit',
                'price' => 'nullable|numeric|gt:0',
                'amount' => 'nullable|numeric|gt:0',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first(),
                ], 422);
            }

            $user = JWTAuth::user();
            $side = strtolower(trim($request->side));
            $orderType = strtolower(trim($request->order_type));

            $coin = Coin::where('id', $request->coin)
                ->where('status', 1)
                ->first();

            if (!$coin) {
                return response()->json([
                    'status' => false,
                    'message' => 'Coin is invalid or inactive',
                ], 422);
            }

            $coinName = strtolower(trim($coin->name));

            if ($coinName === 'usdt') {
                return response()->json([
                    'status' => false,
                    'message' => 'Trade coin cannot be usdt',
                ], 422);
            }

            if ($orderType === 'market' && $side === 'buy' && !$request->filled('amount')) {
                return response()->json([
                    'status' => false,
                    'message' => 'amount is required for market buy order',
                ], 422);
            }

            if ($orderType === 'market' && $side === 'sell' && !$request->filled('amount')) {
                return response()->json([
                    'status' => false,
                    'message' => 'amount is required for market sell order',
                ], 422);
            }

            if ($orderType === 'limit' && (!$request->filled('amount') || !$request->filled('price'))) {
                return response()->json([
                    'status' => false,
                    'message' => 'price and amount are required for limit order',
                ], 422);
            }

            $coinColumn = $coinName;
            $coinFrozenColumn = $coinColumn . '_d';

            if (!Schema::hasColumn('tw_user_coin', $coinColumn) || !Schema::hasColumn('tw_user_coin', $coinFrozenColumn)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Coin balance column not found',
                ], 422);
            }

            $marketPrice = null;
            if ($orderType === 'market') {
                $marketPrice = $this->fetchTickerPrice($coinName);

                if (!$marketPrice || $marketPrice <= 0) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Failed to fetch market price',
                    ], 500);
                }
            }

            $type = $side === 'buy' ? 1 : 2;
            $ordertype = $orderType === 'limit' ? 1 : 2;
            $symbol = strtoupper($coinName) . '/USDT';
            $feeRate = (float) ($coin->bbsxf ?? 0);

            DB::beginTransaction();

            $userCoin = UserCoin::where('userid', $user->id)->lockForUpdate()->first();
            if (!$userCoin) {
                DB::rollBack();
                return response()->json([
                    'status' => false,
                    'message' => 'User coin balance not found',
                ], 422);
            }

            if ($orderType === 'market') {
                if ($side === 'buy') {
                    $coinAmount = (float) $request->amount;
                    $usdtSpend = $coinAmount * $marketPrice;

                    if ((float) ($userCoin->usdt ?? 0) < $usdtSpend) {
                        DB::rollBack();
                        return response()->json([
                            'status' => false,
                            'message' => 'Insufficient USDT balance',
                        ], 422);
                    }

                    $feeCoin = $feeRate > 0 ? ($coinAmount * $feeRate / 100) : 0;
                    $netCoin = max($coinAmount - $feeCoin, 0);

                    UserCoin::where('userid', $user->id)->decrement('usdt', $usdtSpend);
                    UserCoin::where('userid', $user->id)->increment($coinColumn, $netCoin);

                    $afterUsdt = (float) $userCoin->usdt - $usdtSpend;
                    $afterCoin = (float) $userCoin->$coinColumn + $netCoin;

                    $order = Bborder::create([
                        'uid' => $user->id,
                        'account' => $user->username,
                        'type' => $type,
                        'ordertype' => $ordertype,
                        'symbol' => $symbol,
                        'coin' => $coinName,
                        'coinnum' => $coinAmount,
                        'usdtnum' => $usdtSpend,
                        'price' => $marketPrice,
                        'xjprice' => $marketPrice,
                        'addtime' => now()->toDateTimeString(),
                        'tradetime' => now()->toDateTimeString(),
                        'fee' => $feeCoin,
                        'sxfbl' => $feeRate,
                        'status' => 2,
                    ]);

                    $this->createTradeBill($user->id, $user->username, $usdtSpend, 'usdt', $afterUsdt, 2, 'Spot market buy spend USDT');
                    $this->createTradeBill($user->id, $user->username, $netCoin, $coinName, $afterCoin, 1, 'Spot market buy receive coin');

                    DB::commit();

                    return response()->json([
                        'status' => true,
                        'message' => 'Market buy order executed successfully',
                        'data' => [
                            'order_id' => $order->id,
                            'symbol' => $symbol,
                            'side' => $side,
                            'order_type' => $orderType,
                            'price' => (string) $marketPrice,
                            'amount' => (string) $netCoin,
                            'requested_amount' => (string) $coinAmount,
                            'usdt_spent' => (string) $usdtSpend,
                            'fee' => (string) $feeCoin,
                            'status' => 2,
                        ],
                    ], 200);
                }

                $coinAmount = (float) $request->amount;
                if ((float) ($userCoin->$coinColumn ?? 0) < $coinAmount) {
                    DB::rollBack();
                    return response()->json([
                        'status' => false,
                        'message' => 'Insufficient coin balance',
                    ], 422);
                }

                $grossUsdt = $coinAmount * $marketPrice;
                $feeUsdt = $feeRate > 0 ? ($grossUsdt * $feeRate / 100) : 0;
                $netUsdt = max($grossUsdt - $feeUsdt, 0);

                UserCoin::where('userid', $user->id)->decrement($coinColumn, $coinAmount);
                UserCoin::where('userid', $user->id)->increment('usdt', $netUsdt);

                $afterCoin = (float) $userCoin->$coinColumn - $coinAmount;
                $afterUsdt = (float) $userCoin->usdt + $netUsdt;

                $order = Bborder::create([
                    'uid' => $user->id,
                    'account' => $user->username,
                    'type' => $type,
                    'ordertype' => $ordertype,
                    'symbol' => $symbol,
                    'coin' => $coinName,
                    'coinnum' => $coinAmount,
                    'usdtnum' => $netUsdt,
                    'price' => $marketPrice,
                    'xjprice' => $marketPrice,
                    'addtime' => now()->toDateTimeString(),
                    'tradetime' => now()->toDateTimeString(),
                    'fee' => $feeUsdt,
                    'sxfbl' => $feeRate,
                    'status' => 2,
                ]);

                $this->createTradeBill($user->id, $user->username, $coinAmount, $coinName, $afterCoin, 2, 'Spot market sell spend coin');
                $this->createTradeBill($user->id, $user->username, $netUsdt, 'usdt', $afterUsdt, 1, 'Spot market sell receive USDT');

                DB::commit();

                return response()->json([
                    'status' => true,
                    'message' => 'Market sell order executed successfully',
                    'data' => [
                        'order_id' => $order->id,
                        'symbol' => $symbol,
                        'side' => $side,
                        'order_type' => $orderType,
                        'price' => (string) $marketPrice,
                        'amount' => (string) $coinAmount,
                        'usdt_received' => (string) $netUsdt,
                        'fee' => (string) $feeUsdt,
                        'status' => 2,
                    ],
                ], 200);
            }

            $limitPrice = (float) $request->price;
            $coinAmount = (float) $request->amount;

            if ($side === 'buy') {
                $freezeUsdt = $coinAmount * $limitPrice;

                if ((float) ($userCoin->usdt ?? 0) < $freezeUsdt) {
                    DB::rollBack();
                    return response()->json([
                        'status' => false,
                        'message' => 'Insufficient USDT balance',
                    ], 422);
                }

                UserCoin::where('userid', $user->id)->decrement('usdt', $freezeUsdt);
                UserCoin::where('userid', $user->id)->increment('usdt_d', $freezeUsdt);

                $afterUsdt = (float) $userCoin->usdt - $freezeUsdt;

                $order = Bborder::create([
                    'uid' => $user->id,
                    'account' => $user->username,
                    'type' => $type,
                    'ordertype' => $ordertype,
                    'symbol' => $symbol,
                    'coin' => $coinName,
                    'coinnum' => $coinAmount,
                    'usdtnum' => $freezeUsdt,
                    'price' => $limitPrice,
                    'xjprice' => 0,
                    'addtime' => now()->toDateTimeString(),
                    'tradetime' => null,
                    'fee' => 0,
                    'sxfbl' => $feeRate,
                    'status' => 1,
                ]);

                $this->createTradeBill($user->id, $user->username, $freezeUsdt, 'usdt', $afterUsdt, 2, 'Spot limit buy freeze USDT');

                DB::commit();

                return response()->json([
                    'status' => true,
                    'message' => 'Limit buy order placed successfully',
                    'data' => [
                        'order_id' => $order->id,
                        'symbol' => $symbol,
                        'side' => $side,
                        'order_type' => $orderType,
                        'price' => (string) $limitPrice,
                        'amount' => (string) $coinAmount,
                        'usdtnum' => (string) $freezeUsdt,
                        'status' => 1,
                    ],
                ], 200);
            }

            if ((float) ($userCoin->$coinColumn ?? 0) < $coinAmount) {
                DB::rollBack();
                return response()->json([
                    'status' => false,
                    'message' => 'Insufficient coin balance',
                ], 422);
            }

            UserCoin::where('userid', $user->id)->decrement($coinColumn, $coinAmount);
            UserCoin::where('userid', $user->id)->increment($coinFrozenColumn, $coinAmount);

            $afterCoin = (float) $userCoin->$coinColumn - $coinAmount;
            $estimateUsdt = $coinAmount * $limitPrice;

            $order = Bborder::create([
                'uid' => $user->id,
                'account' => $user->username,
                'type' => $type,
                'ordertype' => $ordertype,
                'symbol' => $symbol,
                'coin' => $coinName,
                'coinnum' => $coinAmount,
                'usdtnum' => $estimateUsdt,
                'price' => $limitPrice,
                'xjprice' => 0,
                'addtime' => now()->toDateTimeString(),
                'tradetime' => null,
                'fee' => 0,
                'sxfbl' => $feeRate,
                'status' => 1,
            ]);

            $this->createTradeBill($user->id, $user->username, $coinAmount, $coinName, $afterCoin, 2, 'Spot limit sell freeze coin');

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Limit sell order placed successfully',
                'data' => [
                    'order_id' => $order->id,
                    'symbol' => $symbol,
                    'side' => $side,
                    'order_type' => $orderType,
                    'price' => (string) $limitPrice,
                    'amount' => (string) $coinAmount,
                    'usdtnum' => (string) $estimateUsdt,
                    'status' => 1,
                ],
            ], 200);
        } catch (\Exception $e) {
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }
            \Log::error('Place trade order failed', ['error' => $e->getMessage()]);

            return response()->json([
                'status' => false,
                'message' => 'Failed to place order. Try again later.',
            ], 500);
        }
    }

    public function openOrders(Request $request)
    {
        try {
            $user = JWTAuth::user();
            $page = max((int) $request->input('page', 1), 1);
            $limit = (int) $request->input('limit', 20);
            $limit = max(min($limit, 100), 1);

            $orders = Bborder::where('uid', $user->id)
                ->where('ordertype', 1)
                ->where('status', 1)
                ->orderBy('id', 'desc')
                ->paginate($limit, ['*'], 'page', $page);

            return response()->json([
                'status' => true,
                'message' => 'Open orders retrieved successfully',
                'data' => $orders->items(),
                'pagination' => [
                    'current_page' => $orders->currentPage(),
                    'per_page' => $orders->perPage(),
                    'total' => $orders->total(),
                    'last_page' => $orders->lastPage(),
                ],
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Open orders retrieval failed', ['error' => $e->getMessage()]);

            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve open orders. Try again later.',
            ], 500);
        }
    }

    public function orderHistory(Request $request)
    {
        try {
            $user = JWTAuth::user();
            $page = max((int) $request->input('page', 1), 1);
            $limit = (int) $request->input('limit', 20);
            $limit = max(min($limit, 100), 1);

            $history = Bborder::where('uid', $user->id)
                ->where('status', '!=', 1)
                ->orderBy('id', 'desc')
                ->paginate($limit, ['*'], 'page', $page);

            return response()->json([
                'status' => true,
                'message' => 'Order history retrieved successfully',
                'data' => $history->items(),
                'pagination' => [
                    'current_page' => $history->currentPage(),
                    'per_page' => $history->perPage(),
                    'total' => $history->total(),
                    'last_page' => $history->lastPage(),
                ],
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Order history retrieval failed', ['error' => $e->getMessage()]);

            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve order history. Try again later.',
            ], 500);
        }
    }

    public function cancelOrder(Request $request, $id)
    {
        try {
            $user = JWTAuth::user();

            $order = Bborder::where('id', $id)
                ->where('uid', $user->id)
                ->where('ordertype', 1)
                ->where('status', 1)
                ->first();

            if (!$order) {
                return response()->json([
                    'status' => false,
                    'message' => 'Order not found or cannot be canceled',
                ], 404);
            }

            $coinName = strtolower(trim((string) $order->coin));
            $coinFrozenColumn = $coinName . '_d';

            if (!Schema::hasColumn('tw_user_coin', $coinName) || !Schema::hasColumn('tw_user_coin', $coinFrozenColumn)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Coin balance column not found',
                ], 422);
            }

            DB::beginTransaction();

            $userCoin = UserCoin::where('userid', $user->id)->lockForUpdate()->first();
            if (!$userCoin) {
                DB::rollBack();
                return response()->json([
                    'status' => false,
                    'message' => 'User coin balance not found',
                ], 422);
            }

            if ((int) $order->type === 1) {
                $releaseUsdt = (float) $order->usdtnum;
                UserCoin::where('userid', $user->id)->decrement('usdt_d', $releaseUsdt);
                UserCoin::where('userid', $user->id)->increment('usdt', $releaseUsdt);

                $afterUsdt = (float) $userCoin->usdt + $releaseUsdt;
                $this->createTradeBill($user->id, $user->username, $releaseUsdt, 'usdt', $afterUsdt, 1, 'Spot limit buy cancel release USDT');
            } else {
                $releaseCoin = (float) $order->coinnum;
                UserCoin::where('userid', $user->id)->decrement($coinFrozenColumn, $releaseCoin);
                UserCoin::where('userid', $user->id)->increment($coinName, $releaseCoin);

                $afterCoin = (float) $userCoin->$coinName + $releaseCoin;
                $this->createTradeBill($user->id, $user->username, $releaseCoin, $coinName, $afterCoin, 1, 'Spot limit sell cancel release coin');
            }

            $order->update([
                'status' => 3,
                'tradetime' => now()->toDateTimeString(),
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Order canceled successfully',
            ], 200);
        } catch (\Exception $e) {
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }
            \Log::error('Cancel order failed', ['error' => $e->getMessage()]);

            return response()->json([
                'status' => false,
                'message' => 'Failed to cancel order. Try again later.',
            ], 500);
        }
    }

    public function bborder(Request $request)
    {
        return $this->openOrders($request);
    }

    public function bbhistoryorder(Request $request)
    {
        return $this->orderHistory($request);
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

    private function createTradeBill(int $uid, string $username, float $num, string $coinName, float $afterNum, int $st, string $remark): void
    {
        Bill::create([
            'uid' => $uid,
            'username' => $username,
            'num' => $num,
            'coinname' => strtolower($coinName),
            'afternum' => $afterNum,
            'type' => 13,
            'addtime' => now()->toDateTimeString(),
            'st' => $st,
            'remark' => $remark,
        ]);
    }
}
