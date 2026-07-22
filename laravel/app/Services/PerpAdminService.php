<?php

namespace App\Services;

use App\Models\Bill;
use App\Models\Hysetting;
use App\Models\PerpFill;
use App\Models\PerpPosition;
use App\Models\User;
use App\Models\UserCoin;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class PerpAdminService
{
    public function __construct(
        private readonly PerpTradingService $perp,
    ) {}

    public function winRatePercent(): float
    {
        $hy = Hysetting::query()->find(1);
        $rate = (float) ($hy->perp_win_rate ?? 80);

        return $rate > 0 ? $rate : 80;
    }

    public function updateWinRate(float $rate): float
    {
        $rate = max(0.01, min(1000, $rate));
        Hysetting::query()->where('id', 1)->update(['perp_win_rate' => $rate]);

        return $rate;
    }

    /**
     * @return array{status: bool, message?: string, data?: array}
     */
    public function settingsPayload(): array
    {
        return [
            'status' => true,
            'data' => [
                'perp_win_rate' => $this->winRatePercent(),
            ],
        ];
    }

    public function listPositions(
        string $scope,
        ?string $username,
        ?string $symbol,
        int $perPage,
    ): LengthAwarePaginator {
        $query = PerpPosition::query()->orderByDesc('id');

        if ($scope === 'open') {
            $query->where('status', PerpPosition::STATUS_OPEN);
        } elseif ($scope === 'closed') {
            $query->whereIn('status', [
                PerpPosition::STATUS_CLOSED,
                PerpPosition::STATUS_LIQUIDATED,
            ]);
        }

        if ($username !== null && $username !== '') {
            $query->where('username', 'like', '%' . $username . '%');
        }

        if ($symbol !== null && $symbol !== '') {
            $query->where('symbol', strtoupper(str_replace('/', '', $symbol)));
        }

        return $query->paginate($perPage);
    }

    public function listFills(?string $username, ?string $symbol, int $perPage): LengthAwarePaginator
    {
        $query = PerpFill::query()->orderByDesc('id');

        if ($username !== null && $username !== '') {
            $query->whereIn('uid', function ($sub) use ($username) {
                $sub->select('id')->from('tw_user')->where('username', 'like', '%' . $username . '%');
            });
        }

        if ($symbol !== null && $symbol !== '') {
            $query->where('symbol', strtoupper(str_replace('/', '', $symbol)));
        }

        return $query->paginate($perPage);
    }

    public function pendingAlertData(): array
    {
        $query = PerpPosition::query()
            ->where('status', PerpPosition::STATUS_OPEN)
            ->where('admin_notified', 0);

        $count = (clone $query)->count();
        $positions = (clone $query)->orderByDesc('id')->limit(5)->get();

        return [
            'count' => $count,
            'has_new' => $count > 0,
            'positions' => $positions,
        ];
    }

    public function markNotified(): int
    {
        return PerpPosition::query()
            ->where('status', PerpPosition::STATUS_OPEN)
            ->where('admin_notified', 0)
            ->update(['admin_notified' => 1]);
    }

    public function openPositionsCount(): int
    {
        return PerpPosition::query()
            ->where('status', PerpPosition::STATUS_OPEN)
            ->count();
    }

    public function unnotifiedOpenCount(): int
    {
        return PerpPosition::query()
            ->where('status', PerpPosition::STATUS_OPEN)
            ->where('admin_notified', 0)
            ->count();
    }

    /**
     * @return array{status: bool, message?: string}
     */
    public function setKongyk(int $positionId, int $kongyk): array
    {
        if (!in_array($kongyk, [0, 1, 2], true)) {
            return ['status' => false, 'message' => 'Invalid kongyk.'];
        }

        $position = PerpPosition::query()->find($positionId);
        if (!$position || (int) $position->status !== PerpPosition::STATUS_OPEN) {
            return ['status' => false, 'message' => 'Open position not found.'];
        }

        $position->update(['kongyk' => $kongyk]);

        return ['status' => true, 'message' => 'Successfully.'];
    }

    /**
     * @return array{status: bool, message?: string, data?: array}
     */
    public function settle(int $positionId): array
    {
        $position = PerpPosition::query()->find($positionId);
        if (!$position || (int) $position->status !== PerpPosition::STATUS_OPEN) {
            return ['status' => false, 'message' => 'Open position not found.'];
        }

        $kongyk = (int) ($position->kongyk ?? 0);

        if ($kongyk === 1) {
            return $this->settleForceWin($position);
        }

        if ($kongyk === 2) {
            return $this->settleForceLoss($position);
        }

        $user = User::query()->find($position->uid);
        if (!$user) {
            return ['status' => false, 'message' => 'User not found.'];
        }

        return $this->perp->closePosition($user, $positionId, null, null);
    }

    /**
     * @return array{status: bool, message?: string, data?: array}
     */
    private function settleForceWin(PerpPosition $position): array
    {
        try {
            return DB::transaction(function () use ($position) {
                $locked = PerpPosition::query()
                    ->where('id', $position->id)
                    ->where('status', PerpPosition::STATUS_OPEN)
                    ->lockForUpdate()
                    ->first();

                if (!$locked) {
                    return ['status' => false, 'message' => 'Open position not found.'];
                }

                $user = User::query()->find($locked->uid);
                if (!$user) {
                    return ['status' => false, 'message' => 'User not found.'];
                }

                $margin = (float) $locked->margin;
                $closeQty = (float) $locked->qty;
                $profit = $margin * ($this->winRatePercent() / 100);
                $credit = $margin + $profit;
                $mark = $this->perp->fetchMarkPrice($locked->symbol) ?? (float) $locked->entry_price;

                $userCoin = UserCoin::where('userid', $user->id)->lockForUpdate()->first();
                if (!$userCoin) {
                    return ['status' => false, 'message' => 'User balance not found.'];
                }

                UserCoin::where('userid', $user->id)->decrement('usdt_d', $margin);
                UserCoin::where('userid', $user->id)->increment('usdt', $credit);

                $afterUsdt = (float) $userCoin->usdt + $credit;
                $this->createBill($user, $credit, $afterUsdt, 1, 'Perp admin win ' . $locked->symbol);

                $locked->update([
                    'qty' => 0,
                    'margin' => 0,
                    'status' => PerpPosition::STATUS_CLOSED,
                    'closed_at' => now(),
                    'close_price' => $mark,
                    'realized_pnl' => $profit,
                    'unrealized_pnl' => 0,
                ]);

                $this->recordFill(
                    $user->id,
                    $locked->id,
                    $locked->symbol,
                    $locked->side,
                    'admin_win',
                    $closeQty,
                    $mark,
                    (int) $locked->leverage,
                    -$margin,
                    0,
                    $profit,
                );

                return [
                    'status' => true,
                    'message' => 'Position settled (win).',
                    'data' => ['id' => $locked->id, 'realized_pnl' => $profit],
                ];
            });
        } catch (\Throwable $e) {
            return ['status' => false, 'message' => $e->getMessage() ?: 'Settle failed.'];
        }
    }

    /**
     * @return array{status: bool, message?: string, data?: array}
     */
    private function settleForceLoss(PerpPosition $position): array
    {
        try {
            return DB::transaction(function () use ($position) {
                $locked = PerpPosition::query()
                    ->where('id', $position->id)
                    ->where('status', PerpPosition::STATUS_OPEN)
                    ->lockForUpdate()
                    ->first();

                if (!$locked) {
                    return ['status' => false, 'message' => 'Open position not found.'];
                }

                $user = User::query()->find($locked->uid);
                if (!$user) {
                    return ['status' => false, 'message' => 'User not found.'];
                }

                $margin = (float) $locked->margin;
                $closeQty = (float) $locked->qty;
                $mark = $this->perp->fetchMarkPrice($locked->symbol) ?? (float) $locked->entry_price;
                $pnl = -$margin;

                $userCoin = UserCoin::where('userid', $user->id)->lockForUpdate()->first();
                if (!$userCoin) {
                    return ['status' => false, 'message' => 'User balance not found.'];
                }

                UserCoin::where('userid', $user->id)->decrement('usdt_d', $margin);

                $afterUsdt = (float) $userCoin->usdt;
                $this->createBill($user, $margin, $afterUsdt, 2, 'Perp admin loss ' . $locked->symbol);

                $locked->update([
                    'qty' => 0,
                    'margin' => 0,
                    'status' => PerpPosition::STATUS_CLOSED,
                    'closed_at' => now(),
                    'close_price' => $mark,
                    'realized_pnl' => $pnl,
                    'unrealized_pnl' => 0,
                ]);

                $this->recordFill(
                    $user->id,
                    $locked->id,
                    $locked->symbol,
                    $locked->side,
                    'admin_loss',
                    $closeQty,
                    $mark,
                    (int) $locked->leverage,
                    -$margin,
                    0,
                    $pnl,
                );

                return [
                    'status' => true,
                    'message' => 'Position settled (loss).',
                    'data' => ['id' => $locked->id, 'realized_pnl' => $pnl],
                ];
            });
        } catch (\Throwable $e) {
            return ['status' => false, 'message' => $e->getMessage() ?: 'Settle failed.'];
        }
    }

    public function enrichWithMark(PerpPosition $position): PerpPosition
    {
        $mark = $this->perp->fetchMarkPrice($position->symbol);
        if ($mark && $mark > 0 && (int) $position->status === PerpPosition::STATUS_OPEN) {
            $unreal = \App\Support\PerpMath::unrealizedPnl(
                $position->side,
                (float) $position->entry_price,
                $mark,
                (float) $position->qty,
            );
            $position->setAttribute('mark_price', $mark);
            $position->setAttribute('unrealized_pnl', $unreal);
        }

        return $position;
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
        float $pnl,
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
            'type' => PerpTradingService::BILL_TYPE_PERP,
            'addtime' => now()->toDateTimeString(),
            'st' => $st,
            'remark' => $remark,
        ]);
    }
}
