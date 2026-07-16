<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DepositResource extends JsonResource
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
            'coin' => $this->coin,
            'method' => (int) $this->method,
            'method_label' => $this->methodLabel(),
            'address' => $this->address,
            'num' => $this->num,
            'num_real' => $this->num_real,
            'payimg' => $this->payimg,
            'msg' => $this->msg,
            'addtime' => $this->addtime,
            'updatetime' => $this->updatetime,
            'status' => (int) $this->status,
            'status_label' => $this->statusLabel(),
        ];
    }

    private function methodLabel(): string
    {
        return match ((int) $this->method) {
            1 => 'Bank transfer',
            2 => 'Electronic wallet',
            default => 'Other',
        };
    }

    private function statusLabel(): string
    {
        return match ((int) $this->status) {
            1 => 'Pending review',
            2 => 'Approved',
            3 => 'Rejected',
            default => 'Unknown',
        };
    }
}
