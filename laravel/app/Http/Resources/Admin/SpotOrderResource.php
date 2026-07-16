<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SpotOrderResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'uid' => (int) $this->uid,
            'account' => $this->account,
            'type' => (int) $this->type,
            'type_label' => $this->typeLabel(),
            'ordertype' => (int) $this->ordertype,
            'ordertype_label' => $this->ordertypeLabel(),
            'symbol' => $this->symbol,
            'coin' => $this->coin,
            'coinnum' => $this->coinnum,
            'usdtnum' => $this->usdtnum,
            'price' => $this->price,
            'xjprice' => $this->xjprice,
            'addtime' => $this->addtime,
            'tradetime' => $this->tradetime,
            'fee' => $this->fee,
            'sxfbl' => $this->sxfbl,
            'status' => (int) $this->status,
            'status_label' => $this->statusLabel(),
        ];
    }

    private function typeLabel(): string
    {
        return match ((int) $this->type) {
            1 => 'Buy',
            2 => 'Sell',
            default => 'Unknown',
        };
    }

    private function ordertypeLabel(): string
    {
        return match ((int) $this->ordertype) {
            1 => 'Limit',
            2 => 'Market',
            default => 'Unknown',
        };
    }

    private function statusLabel(): string
    {
        return match ((int) $this->status) {
            1 => 'Placing order',
            2 => 'Completed',
            3 => 'Cancelled',
            default => 'Unknown',
        };
    }
}
