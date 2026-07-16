<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContractOrderResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'uid' => (int) $this->uid,
            'username' => $this->username,
            'coinname' => $this->coinname,
            'num' => $this->num,
            'hybl' => $this->hybl,
            'hyzd' => (int) $this->hyzd,
            'hyzd_label' => $this->hyzdLabel(),
            'status' => (int) $this->status,
            'status_label' => $this->statusLabel(),
            'is_win' => (int) $this->is_win,
            'is_win_label' => $this->isWinLabel(),
            'buytime' => $this->buytime,
            'selltime' => $this->selltime,
            'intselltime' => (int) $this->intselltime,
            'buyprice' => $this->buyprice,
            'sellprice' => $this->sellprice,
            'ploss' => $this->ploss,
            'time' => (int) $this->time,
            'kongyk' => (int) $this->kongyk,
            'kongyk_label' => $this->kongykLabel(),
            'invit' => $this->invit,
            'tznum' => (int) $this->tznum,
            'balance_before' => $this->balance_before,
            'balance_after' => $this->balance_after,
            'profit_loss' => $this->profit_loss,
        ];
    }

    private function hyzdLabel(): string
    {
        return match ((int) $this->hyzd) {
            1 => 'Buy increase',
            2 => 'Buy decrease',
            default => 'Unknown',
        };
    }

    private function statusLabel(): string
    {
        return match ((int) $this->status) {
            1 => 'Pending settlement',
            2 => 'Settled',
            3 => 'Invalid',
            default => 'Unknown',
        };
    }

    private function isWinLabel(): string
    {
        return match ((int) $this->is_win) {
            0 => 'Pending',
            1 => 'Profit',
            2 => 'Loss',
            default => 'Unknown',
        };
    }

    private function kongykLabel(): string
    {
        return match ((int) $this->kongyk) {
            0 => 'Normal',
            1 => 'Profit',
            2 => 'Loss',
            default => 'Unknown',
        };
    }
}
