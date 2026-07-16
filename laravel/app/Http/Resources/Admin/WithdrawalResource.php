<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WithdrawalResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'userid' => (int) $this->userid,
            'username' => $this->username,
            'coinname' => $this->coinname,
            'wallet' => $this->wallet,
            'method_label' => $this->methodLabel(),
            'address' => $this->address,
            'num' => $this->num,
            'fee' => $this->fee,
            'mum' => $this->mum,
            'txid' => $this->txid,
            'addtime' => $this->addtime,
            'endtime' => $this->endtime,
            'status' => (int) $this->status,
            'status_label' => $this->statusLabel(),
        ];
    }

    private function methodLabel(): string
    {
        $wallet = strtoupper(trim((string) ($this->wallet ?? '')));

        return match ($wallet) {
            'BANK' => 'Bank transfer',
            'ERC20', 'TRC20', 'BEP20' => 'Electronic wallet',
            default => $wallet !== '' ? $wallet : 'Other',
        };
    }

    private function statusLabel(): string
    {
        return match ((int) $this->status) {
            1 => 'Pending review',
            2 => 'Completed',
            3 => 'Not approved',
            default => 'Unknown',
        };
    }
}
