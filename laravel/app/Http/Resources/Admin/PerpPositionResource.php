<?php

namespace App\Http\Resources\Admin;

use App\Models\PerpPosition;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PerpPositionResource extends JsonResource
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
            'symbol' => $this->symbol,
            'side' => $this->side,
            'qty' => (string) $this->qty,
            'entry_price' => (string) $this->entry_price,
            'leverage' => (int) $this->leverage,
            'margin' => (string) $this->margin,
            'liq_price' => (string) $this->liq_price,
            'unrealized_pnl' => (string) ($this->unrealized_pnl ?? 0),
            'mark_price' => $this->mark_price ?? null,
            'status' => (int) $this->status,
            'status_label' => $this->statusLabel(),
            'kongyk' => (int) ($this->kongyk ?? 0),
            'kongyk_label' => $this->kongykLabel(),
            'admin_notified' => (int) ($this->admin_notified ?? 0),
            'opened_at' => $this->opened_at?->toDateTimeString(),
            'closed_at' => $this->closed_at?->toDateTimeString(),
            'close_price' => $this->close_price !== null ? (string) $this->close_price : null,
            'realized_pnl' => $this->realized_pnl !== null ? (string) $this->realized_pnl : null,
        ];
    }

    private function statusLabel(): string
    {
        return match ((int) $this->status) {
            PerpPosition::STATUS_OPEN => 'Open',
            PerpPosition::STATUS_CLOSED => 'Closed',
            PerpPosition::STATUS_LIQUIDATED => 'Liquidated',
            default => 'Unknown',
        };
    }

    private function kongykLabel(): string
    {
        return match ((int) ($this->kongyk ?? 0)) {
            0 => 'Normal',
            1 => 'Profit',
            2 => 'Loss',
            default => 'Unknown',
        };
    }
}
