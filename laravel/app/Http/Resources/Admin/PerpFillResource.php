<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PerpFillResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'uid' => (int) $this->uid,
            'position_id' => (int) $this->position_id,
            'symbol' => $this->symbol,
            'side' => $this->side,
            'action' => $this->action,
            'qty' => (string) $this->qty,
            'price' => (string) $this->price,
            'leverage' => (int) $this->leverage,
            'margin_delta' => (string) $this->margin_delta,
            'fee' => (string) $this->fee,
            'pnl' => (string) $this->pnl,
            'created_at' => $this->created_at?->toDateTimeString(),
        ];
    }
}
