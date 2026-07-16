<?php

namespace App\Services;

use App\Models\Bill;
use App\Models\Hyorder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class ContractOrderBalanceService
{
    /**
     * @param  Collection<int, Hyorder>  $orders
     * @return array<int, array{balance_before: ?float, balance_after: ?float, profit_loss: ?float}>
     */
    public function forOrders(Collection $orders): array
    {
        if ($orders->isEmpty()) {
            return [];
        }

        $balances = [];

        foreach ($orders as $order) {
            // The buy bill is created a fraction of a second after the order row,
            // so its addtime can be 1-2s past order.buytime. Match within a small
            // window and on the staked amount instead of an exact timestamp.
            $buyTime = Carbon::parse($order->buytime);

            $buyBill = Bill::query()
                ->where('uid', $order->uid)
                ->where('type', 3)
                ->where('num', $order->num)
                ->whereBetween('addtime', [
                    $buyTime->copy()->subSeconds(2)->format('Y-m-d H:i:s'),
                    $buyTime->copy()->addSeconds(10)->format('Y-m-d H:i:s'),
                ])
                ->orderBy('id')
                ->first(['id', 'num', 'afternum']);

            if (!$buyBill) {
                $balances[$order->id] = [
                    'balance_before' => null,
                    'balance_after' => null,
                    'profit_loss' => null,
                ];
                continue;
            }

            $settleBill = Bill::query()
                ->where('uid', $order->uid)
                ->where('type', 4)
                ->where('id', '>', $buyBill->id)
                ->where(function ($query) use ($order) {
                    $query->where('remark', 'like', '%#' . $order->id)
                        ->orWhereIn('remark', ['Trade win bonus', 'Trade loss refund'])
                        ->orWhere('remark', 'like', 'Trade win bonus #%')
                        ->orWhere('remark', 'like', 'Trade loss refund #%');
                })
                ->orderBy('id')
                ->first(['num', 'afternum']);

            $balanceBefore = round((float) $buyBill->afternum + (float) $buyBill->num, 2);
            $balanceAfter = $settleBill ? round((float) $settleBill->afternum, 2) : null;
            $profitLoss = $balanceAfter !== null
                ? round($balanceAfter - $balanceBefore, 2)
                : null;

            $balances[$order->id] = [
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'profit_loss' => $profitLoss,
            ];
        }

        return $balances;
    }
}
