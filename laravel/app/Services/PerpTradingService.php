<?php

namespace App\Services;

use App\Models\Bill;
use App\Models\Hysetting;
use App\Models\PerpFill;
use App\Models\PerpPosition;
use App\Models\User;
use App\Models\UserCoin;
use App\Support\PerpMath;
use App\Support\TradingSymbol;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PerpTradingService
{
    public const MAX_LEVERAGE = 100;

    public const DEFAULT_MAINT_MARGIN_RATE = 0.005;

    public const DEFAULT_FEE_PERCENT = 0.05;

    public const BILL_TYPE_PERP = 20;

    /** @var list<string> */
    public const ALLOWED_SYMBOLS = [
        'BTCUSDT',
        'ETHUSDT',
        'SOLUSDT',
        'DOTUSDT',
        'XAUTUSDT',
        'XTZUSDT',
        'ADAUSDT',
        'MLNUSDT',
        'YFIUSDT',
        'ETCUSDT',
        'XRPUSDT',
        'LTCUSDT',
        'USDCUSDT',
        'KNCUSDT',
        'DOGEUSDT',
    ];


    /** @return array{status: false, code: string, message: string} */
    private function fail(string $code, string $message): array
    {
        return ['status' => false, 'code' => $code, 'message' => $message];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array{status: true, code: string, message: string, data?: array}
     */
    private function ok(string $code, string $message, array $data = []): array
    {
        $out = ['status' => true, 'code' => $code, 'message' => $message];
        if ($data !== []) {
            $out['data'] = $data;
        }

        return $out;
    }

    public function settings(): array
    {
        $hy = Hysetting::query()->find(1);
        $fee = (float) ($hy->hy_sxf ?? self::DEFAULT_FEE_PERCENT);
        if ($fee <= 0) {
            $fee = self::DEFAULT_FEE_PERCENT;
        }

        return [
            'fee_rate_percent' => $fee,
            'maint_margin_rate' => self::DEFAULT_MAINT_MARGIN_RATE,
            'max_leverage' => self::MAX_LEVERAGE,
            'symbols' => self::ALLOWED_SYMBOLS,
        ];
    }

    public function balance(User $user): array
    {
        $userCoin = UserCoin::where('userid', $user->id)->first();
        $available = (float) ($userCoin->usdt ?? 0);
        $frozen = (float) ($userCoin->usdt_d ?? 0);

        $unrealized = 0.0;
        foreach ($this->positions($user) as $position) {
            $unrealized += (float) ($position['unrealized_pnl'] ?? 0);
        }

        $wallet = $this->formatUsdt($available);
        $margin = $this->formatUsdt($frozen);

        return [
            'available_usdt' => $wallet,
            'frozen_margin_usdt' => $margin,
            'wallet_balance' => $wallet,
            'margin_balance' => $margin,
            'unrealized_pnl' => $this->formatUsdt($unrealized),
        ];
    }

    private function formatUsdt(float $value): string
    {
        return number_format($value, 2, '.', '');
    }

    public function positions(User $user): array
    {
        $positions = PerpPosition::where('uid', $user->id)
            ->where('status', PerpPosition::STATUS_OPEN)
            ->orderByDesc('id')
            ->get();

        $out = [];
        foreach ($positions as $position) {
            $out[] = $this->enrichPosition($position);
        }

        return $out;
    }

    public function history(User $user, int $limit = 50): array
    {
        return PerpPosition::where('uid', $user->id)
            ->whereIn('status', [PerpPosition::STATUS_CLOSED, PerpPosition::STATUS_LIQUIDATED])
            ->orderByDesc('id')
            ->limit($limit)
            ->get()
            ->map(fn ($p) => $this->positionToArray($p))
            ->all();
    }

    /**
     * @return array{status: bool, message?: string, data?: array}
     */
    public function placeOrder(User $user, string $symbol, string $side, float $qty, int $leverage): array
    {
        if ((int) ($user->trade_locked ?? 0) === 1) {
            return $this->fail('trade_locked', $user->tradeLockMessage());
        }

        $symbol = TradingSymbol::normalize($symbol);
        if (!in_array($symbol, self::ALLOWED_SYMBOLS, true)) {
            return $this->fail('symbol_unsupported', 'Symbol not supported for perpetual trading.');
        }

        $side = strtolower(trim($side));
        if (!in_array($side, ['buy', 'sell'], true)) {
            return $this->fail('invalid_side', 'side must be buy or sell.');
        }

        if ($qty <= 0) {
            return $this->fail('invalid_qty', 'qty must be greater than 0.');
        }

        $leverage = max(1, min(self::MAX_LEVERAGE, $leverage));

        $mark = $this->fetchMarkPrice($symbol);
        if (!$mark || $mark <= 0) {
            return $this->fail('price_fetch_failed', 'Failed to fetch market price.');
        }

        $positionSide = $side === 'buy' ? 'long' : 'short';
        $cfg = $this->settings();
        $feeRate = (float) $cfg['fee_rate_percent'];
        $maint = (float) $cfg['maint_margin_rate'];

        try {
            return DB::transaction(function () use (
                $user,
                $symbol,
                $positionSide,
                $qty,
                $leverage,
                $mark,
                $feeRate,
                $maint
            ) {
                $open = PerpPosition::where('uid', $user->id)
                    ->where('symbol', $symbol)
                    ->where('status', PerpPosition::STATUS_OPEN)
                    ->lockForUpdate()
                    ->first();

                if (!$open) {
                    return $this->openPosition($user, $symbol, $positionSide, $qty, $leverage, $mark, $feeRate, $maint);
                }

                if ($open->side === $positionSide) {
                    return $this->increasePosition($user, $open, $qty, $leverage, $mark, $feeRate, $maint);
                }

                $closeQty = min($qty, (float) $open->qty);
                $result = $this->reducePosition($user, $open, $closeQty, $mark, $feeRate, $maint, 'reduce');
                if (!$result['status']) {
                    return $result;
                }

                $remaining = $qty - $closeQty;
                if ($remaining > 0) {
                    $open->refresh();
                    if ((int) $open->status === PerpPosition::STATUS_OPEN) {
                        return $this->fail(
                            'close_before_flip',
                            'Close existing position before flipping side.'
                        );
                    }

                    return $this->openPosition(
                        $user,
                        $symbol,
                        $positionSide,
                        $remaining,
                        $leverage,
                        $mark,
                        $feeRate,
                        $maint
                    );
                }

                return $result;
            });
        } catch (\Throwable $e) {
            Log::error('Perp placeOrder failed', ['error' => $e->getMessage()]);

            return $this->fail('order_failed', 'Failed to place perpetual order.');
        }
    }

    /**
     * @return array{status: bool, message?: string, data?: array}
     */
    public function closePosition(User $user, ?int $positionId, ?string $symbol, ?float $qty): array
    {
        $symbolNorm = $symbol ? TradingSymbol::normalize($symbol) : null;

        try {
            return DB::transaction(function () use ($user, $positionId, $symbolNorm, $qty) {
                $query = PerpPosition::where('uid', $user->id)
                    ->where('status', PerpPosition::STATUS_OPEN)
                    ->lockForUpdate();

                if ($positionId) {
                    $query->where('id', $positionId);
                } elseif ($symbolNorm) {
                    $query->where('symbol', $symbolNorm);
                } else {
                    return $this->fail('missing_position_ref', 'position_id or symbol required.');
                }

                $position = $query->first();
                if (!$position) {
                    return $this->fail('position_not_found', 'Open position not found.');
                }

                $closeQty = $qty && $qty > 0 ? min($qty, (float) $position->qty) : (float) $position->qty;
                $mark = $this->fetchMarkPrice($position->symbol);
                if (!$mark || $mark <= 0) {
                    return $this->fail('price_fetch_failed', 'Failed to fetch market price.');
                }

                $cfg = $this->settings();
                $action = $closeQty >= (float) $position->qty ? 'close' : 'reduce';

                return $this->reducePosition(
                    $user,
                    $position,
                    $closeQty,
                    $mark,
                    (float) $cfg['fee_rate_percent'],
                    (float) $cfg['maint_margin_rate'],
                    $action
                );
            });
        } catch (\Throwable $e) {
            Log::error('Perp close failed', ['error' => $e->getMessage()]);

            return $this->fail('close_failed', 'Failed to close position.');
        }
    }

    public function processLiquidations(): int
    {
        $count = 0;
        $positions = PerpPosition::where('status', PerpPosition::STATUS_OPEN)
            ->orderBy('id')
            ->limit(200)
            ->get();

        $priceCache = [];

        foreach ($positions as $position) {
            $sym = $position->symbol;
            if (!isset($priceCache[$sym])) {
                $priceCache[$sym] = $this->fetchMarkPrice($sym);
            }
            $mark = $priceCache[$sym];
            if (!$mark || $mark <= 0) {
                continue;
            }

            $this->refreshPositionMetrics($position, $mark);
            if (!PerpMath::shouldLiquidate($position->side, $mark, (float) $position->liq_price)) {
                continue;
            }

            try {
                DB::transaction(function () use ($position, $mark, &$count) {
                    $locked = PerpPosition::where('id', $position->id)
                        ->where('status', PerpPosition::STATUS_OPEN)
                        ->lockForUpdate()
                        ->first();
                    if (!$locked) {
                        return;
                    }

                    $this->liquidatePosition($locked, $mark);
                    $count++;
                });
            } catch (\Throwable $e) {
                Log::warning('Perp liquidation skipped', [
                    'position_id' => $position->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return $count;
    }

    private function openPosition(
        User $user,
        string $symbol,
        string $side,
        float $qty,
        int $leverage,
        float $mark,
        float $feeRate,
        float $maint
    ): array {
        $notional = PerpMath::notional($qty, $mark);
        $margin = PerpMath::marginRequired($notional, $leverage);
        $fee = PerpMath::fee($notional, $feeRate);
        $totalCost = $margin + $fee;

        $userCoin = UserCoin::where('userid', $user->id)->lockForUpdate()->first();
        if (!$userCoin || (float) $userCoin->usdt < $totalCost) {
            return $this->fail('insufficient_balance', 'Insufficient USDT balance.');
        }

        $liq = PerpMath::liquidationPrice($side, $mark, $leverage, $maint);

        UserCoin::where('userid', $user->id)->decrement('usdt', $totalCost);
        UserCoin::where('userid', $user->id)->increment('usdt_d', $margin);

        $afterUsdt = (float) $userCoin->usdt - $totalCost;
        $this->createBill($user, $totalCost, $afterUsdt, 2, 'Perp open margin+fee ' . $symbol);

        $position = PerpPosition::create([
            'uid' => $user->id,
            'username' => $user->username,
            'symbol' => $symbol,
            'side' => $side,
            'qty' => $qty,
            'entry_price' => $mark,
            'leverage' => $leverage,
            'margin' => $margin,
            'liq_price' => $liq,
            'unrealized_pnl' => 0,
            'status' => PerpPosition::STATUS_OPEN,
            'opened_at' => now(),
        ]);

        $this->recordFill($user->id, $position->id, $symbol, $side, 'open', $qty, $mark, $leverage, $margin, $fee, 0);

        return $this->ok(
            'order_success',
            'Position opened.',
            $this->enrichPosition($position->fresh(), $mark)
        );
    }

    private function increasePosition(
        User $user,
        PerpPosition $position,
        float $addQty,
        int $leverage,
        float $mark,
        float $feeRate,
        float $maint
    ): array {
        $notional = PerpMath::notional($addQty, $mark);
        $addMargin = PerpMath::marginRequired($notional, $leverage);
        $fee = PerpMath::fee($notional, $feeRate);
        $totalCost = $addMargin + $fee;

        $userCoin = UserCoin::where('userid', $user->id)->lockForUpdate()->first();
        if (!$userCoin || (float) $userCoin->usdt < $totalCost) {
            return $this->fail('insufficient_balance', 'Insufficient USDT balance.');
        }

        $newQty = (float) $position->qty + $addQty;
        $newEntry = PerpMath::weightedEntry((float) $position->qty, (float) $position->entry_price, $addQty, $mark);
        $newMargin = (float) $position->margin + $addMargin;
        $newLev = $leverage;
        $liq = PerpMath::liquidationPrice($position->side, $newEntry, $newLev, $maint);

        UserCoin::where('userid', $user->id)->decrement('usdt', $totalCost);
        UserCoin::where('userid', $user->id)->increment('usdt_d', $addMargin);

        $afterUsdt = (float) $userCoin->usdt - $totalCost;
        $this->createBill($user, $totalCost, $afterUsdt, 2, 'Perp increase ' . $position->symbol);

        $position->update([
            'qty' => $newQty,
            'entry_price' => $newEntry,
            'leverage' => $newLev,
            'margin' => $newMargin,
            'liq_price' => $liq,
        ]);

        $this->recordFill(
            $user->id,
            $position->id,
            $position->symbol,
            $position->side,
            'increase',
            $addQty,
            $mark,
            $newLev,
            $addMargin,
            $fee,
            0
        );

        return $this->ok(
            'order_success',
            'Position increased.',
            $this->enrichPosition($position->fresh(), $mark)
        );
    }

    private function reducePosition(
        User $user,
        PerpPosition $position,
        float $closeQty,
        float $mark,
        float $feeRate,
        float $maint,
        string $action
    ): array {
        $totalQty = (float) $position->qty;
        if ($closeQty <= 0 || $totalQty <= 0) {
            return $this->fail('invalid_close_qty', 'Invalid close quantity.');
        }

        $pnl = PerpMath::unrealizedPnl($position->side, (float) $position->entry_price, $mark, $closeQty);
        $closeNotional = PerpMath::notional($closeQty, $mark);
        $fee = PerpMath::fee($closeNotional, $feeRate);
        $marginRelease = (float) $position->margin * ($closeQty / $totalQty);

        $userCoin = UserCoin::where('userid', $user->id)->lockForUpdate()->first();
        if (!$userCoin) {
            return $this->fail('balance_unavailable', 'User balance not found.');
        }

        $credit = $marginRelease + $pnl - $fee;

        UserCoin::where('userid', $user->id)->decrement('usdt_d', $marginRelease);
        if ($credit >= 0) {
            UserCoin::where('userid', $user->id)->increment('usdt', $credit);
        } else {
            UserCoin::where('userid', $user->id)->decrement('usdt', abs($credit));
        }

        $afterUsdt = (float) $userCoin->usdt + $credit;
        $this->createBill($user, abs($credit), $afterUsdt, $credit >= 0 ? 1 : 2, 'Perp ' . $action . ' ' . $position->symbol);

        $isFull = $closeQty >= $totalQty - 1e-12;

        if ($isFull) {
            $position->update([
                'qty' => 0,
                'margin' => 0,
                'status' => PerpPosition::STATUS_CLOSED,
                'closed_at' => now(),
                'close_price' => $mark,
                'realized_pnl' => $pnl - $fee,
                'unrealized_pnl' => 0,
            ]);
            $fillAction = $action === 'reduce' ? 'close' : $action;
        } else {
            $newQty = $totalQty - $closeQty;
            $newMargin = (float) $position->margin - $marginRelease;
            $liq = PerpMath::liquidationPrice(
                $position->side,
                (float) $position->entry_price,
                (int) $position->leverage,
                $maint
            );
            $position->update([
                'qty' => $newQty,
                'margin' => $newMargin,
                'liq_price' => $liq,
            ]);
            $fillAction = 'reduce';
        }

        $this->recordFill(
            $user->id,
            $position->id,
            $position->symbol,
            $position->side,
            $fillAction,
            $closeQty,
            $mark,
            (int) $position->leverage,
            -$marginRelease,
            $fee,
            $pnl
        );

        return $this->ok(
            $isFull ? 'close_success' : 'position_reduced',
            $isFull ? 'Position closed.' : 'Position reduced.',
            $this->positionToArray($position->fresh())
        );
    }

    private function liquidatePosition(PerpPosition $position, float $mark): void
    {
        $user = User::find($position->uid);
        if (!$user) {
            return;
        }

        $margin = (float) $position->margin;
        $closeQty = (float) $position->qty;
        $pnl = -$margin;

        $userCoin = UserCoin::where('userid', $user->id)->lockForUpdate()->first();
        if ($userCoin) {
            UserCoin::where('userid', $user->id)->decrement('usdt_d', $margin);
            $afterUsdt = (float) $userCoin->usdt;
            $this->createBill($user, $margin, $afterUsdt, 2, 'Perp liquidated ' . $position->symbol);
        }

        $position->update([
            'qty' => 0,
            'margin' => 0,
            'status' => PerpPosition::STATUS_LIQUIDATED,
            'closed_at' => now(),
            'close_price' => $mark,
            'realized_pnl' => $pnl,
            'unrealized_pnl' => 0,
        ]);

        $this->recordFill(
            $user->id,
            $position->id,
            $position->symbol,
            $position->side,
            'liquidate',
            $closeQty,
            $mark,
            (int) $position->leverage,
            -$margin,
            0,
            $pnl
        );
    }

    private function enrichPosition(PerpPosition $position, ?float $mark = null): array
    {
        $mark = $mark ?? $this->fetchMarkPrice($position->symbol);
        if ($mark && $mark > 0) {
            $this->refreshPositionMetrics($position, $mark);
            $position->refresh();
        }

        $data = $this->positionToArray($position);
        $data['mark_price'] = $mark ? (string) $mark : null;

        return $data;
    }

    private function refreshPositionMetrics(PerpPosition $position, float $mark): void
    {
        $cfg = $this->settings();
        $unreal = PerpMath::unrealizedPnl(
            $position->side,
            (float) $position->entry_price,
            $mark,
            (float) $position->qty
        );
        $liq = PerpMath::liquidationPrice(
            $position->side,
            (float) $position->entry_price,
            (int) $position->leverage,
            (float) $cfg['maint_margin_rate']
        );

        $position->update([
            'unrealized_pnl' => $unreal,
            'liq_price' => $liq,
        ]);
    }

    private function positionToArray(PerpPosition $position): array
    {
        return [
            'id' => $position->id,
            'symbol' => $position->symbol,
            'side' => $position->side,
            'qty' => (string) $position->qty,
            'entry_price' => (string) $position->entry_price,
            'leverage' => (int) $position->leverage,
            'margin' => (string) $position->margin,
            'liq_price' => (string) $position->liq_price,
            'unrealized_pnl' => (string) $position->unrealized_pnl,
            'status' => (int) $position->status,
            'opened_at' => $position->opened_at?->toDateTimeString(),
            'closed_at' => $position->closed_at?->toDateTimeString(),
            'close_price' => $position->close_price !== null ? (string) $position->close_price : null,
            'realized_pnl' => $position->realized_pnl !== null ? (string) $position->realized_pnl : null,
        ];
    }

    private function recordFill(
        int $uid,
        int $positionId,
        string $symbol,
        string $side,
        string $action,
        float $qty,
        float $price,
        int $leverage,
        float $marginDelta,
        float $fee,
        float $pnl
    ): void {
        PerpFill::create([
            'uid' => $uid,
            'position_id' => $positionId,
            'symbol' => $symbol,
            'side' => $side,
            'action' => $action,
            'qty' => $qty,
            'price' => $price,
            'leverage' => $leverage,
            'margin_delta' => $marginDelta,
            'fee' => $fee,
            'pnl' => $pnl,
            'created_at' => now(),
        ]);
    }

    private function createBill(User $user, float $num, float $afterNum, int $st, string $remark): void
    {
        Bill::create([
            'uid' => $user->id,
            'username' => $user->username,
            'num' => $num,
            'coinname' => 'usdt',
            'afternum' => $afterNum,
            'type' => self::BILL_TYPE_PERP,
            'addtime' => now()->toDateTimeString(),
            'st' => $st,
            'remark' => $remark,
        ]);
    }

    public function fetchMarkPrice(string $symbol): ?float
    {
        $normalized = TradingSymbol::normalize($symbol);
        if (!str_ends_with($normalized, 'USDT')) {
            return null;
        }

        $url = "https://api.binance.com/api/v3/ticker/price?symbol={$normalized}";
        try {
            $response = Http::timeout(10)->get($url);
            if (!$response->ok()) {
                return null;
            }
            $price = (float) $response->json('price');

            return $price > 0 ? $price : null;
        } catch (\Throwable) {
            return null;
        }
    }
}
