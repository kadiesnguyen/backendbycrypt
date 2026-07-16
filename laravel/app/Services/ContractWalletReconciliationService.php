<?php

namespace App\Services;

use App\Models\Bill;
use App\Models\Hyorder;
use Illuminate\Support\Collection;

/**
 * Fixes contract settlement bill records to match payout rules.
 * Does NOT modify live wallet balances — use HyorderSettlementService for new settlements.
 */
class ContractWalletReconciliationService
{
    /**
     * @return array{orders_checked: int, bills_fixed: int, errors: list<string>}
     */
    public function reconcile(?int $userId = null): array
    {
        $result = [
            'orders_checked' => 0,
            'bills_fixed' => 0,
            'errors' => [],
        ];

        $query = Hyorder::query()
            ->where('status', 2)
            ->whereIn('is_win', [1, 2])
            ->orderBy('uid')
            ->orderBy('id');

        if ($userId !== null) {
            $query->where('uid', $userId);
        }

        $orders = $query->get()->groupBy('uid');

        /** @var Collection<int, Hyorder> $userOrders */
        foreach ($orders as $uid => $userOrders) {
            try {
                $this->reconcileUserOrders((int) $uid, $userOrders, $result);
            } catch (\Throwable $e) {
                $result['errors'][] = "User {$uid}: {$e->getMessage()}";
            }
        }

        return $result;
    }

    /**
     * @param  Collection<int, Hyorder> $orders
     * @param  array{orders_checked: int, bills_fixed: int, errors: list<string>} $result
     */
    protected function reconcileUserOrders(int $userId, Collection $orders, array &$result): void
    {
        foreach ($orders as $order) {
            $result['orders_checked']++;
            $buyBill = $this->findBuyBill($order);

            if (!$buyBill) {
                continue;
            }

            $stake = (float) $order->num;
            $credit = ContractSettlementMath::settlementCredit($order);
            $remark = ContractSettlementMath::settlementRemark($order);
            $balanceBefore = round((float) $buyBill->afternum + (float) $buyBill->num, 2);
            $expectedAfter = round($balanceBefore - $stake + $credit, 2);

            $settleBill = $this->findSettlementBill($order, $buyBill, $remark);

            if (!$settleBill) {
                continue;
            }

            $this->fixSettlementBill($settleBill, $credit, $remark, $expectedAfter, $result);
        }
    }

    protected function findBuyBill(Hyorder $order): ?Bill
    {
        $bill = Bill::query()
            ->where('uid', $order->uid)
            ->where('type', 3)
            ->where('addtime', $order->buytime)
            ->orderBy('id')
            ->first();

        if ($bill) {
            return $bill;
        }

        return Bill::query()
            ->where('uid', $order->uid)
            ->where('type', 3)
            ->where('remark', 'like', 'Buy ' . $order->coinname . '%')
            ->where('num', $order->num)
            ->orderByDesc('id')
            ->first();
    }

    protected function findSettlementBill(Hyorder $order, Bill $buyBill, string $remark): ?Bill
    {
        $exact = Bill::query()
            ->where('uid', $order->uid)
            ->where('type', 4)
            ->where('id', '>', $buyBill->id)
            ->where(function ($query) use ($remark) {
                $query->where('remark', $remark)
                    ->orWhere('remark', 'like', $remark . ' (%');
            })
            ->orderBy('id')
            ->first();

        if ($exact) {
            return $exact;
        }

        $nextBuyId = Bill::query()
            ->where('uid', $order->uid)
            ->where('type', 3)
            ->where('id', '>', $buyBill->id)
            ->orderBy('id')
            ->value('id');

        $legacy = Bill::query()
            ->where('uid', $order->uid)
            ->where('type', 4)
            ->where('id', '>', $buyBill->id)
            ->where(function ($query) {
                $query->where('remark', 'Trade win bonus')
                    ->orWhere('remark', 'like', 'Trade win bonus #%')
                    ->orWhere('remark', 'Trade loss refund')
                    ->orWhere('remark', 'like', 'Trade loss refund #%');
            });

        if ($nextBuyId) {
            $legacy->where('id', '<', $nextBuyId);
        }

        return $legacy->orderBy('id')->first();
    }

    /**
     * @param  array{orders_checked: int, bills_fixed: int, errors: list<string>} $result
     */
    protected function fixSettlementBill(
        Bill $settleBill,
        float $expectedCredit,
        string $remark,
        float $expectedAfter,
        array &$result,
    ): void {
        $updates = [];

        if ($settleBill->remark !== $remark) {
            $updates['remark'] = $remark;
        }

        if (abs((float) $settleBill->num - $expectedCredit) >= 0.01) {
            $updates['num'] = $expectedCredit;
        }

        if (abs((float) $settleBill->afternum - $expectedAfter) >= 0.01) {
            $updates['afternum'] = $expectedAfter;
        }

        if ($updates !== []) {
            $settleBill->update($updates);
            $result['bills_fixed']++;
        }
    }
}
