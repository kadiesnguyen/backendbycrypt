<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransferResource extends JsonResource
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
            'from_coin' => $this->from_coin,
            'to_coin' => $this->to_coin,
            'from_amount' => $this->from_amount,
            'to_amount' => $this->to_amount,
            'from_rate_usdt' => $this->from_rate_usdt,
            'to_rate_usdt' => $this->to_rate_usdt,
            'convert_rate' => $this->convertRateLabel(),
            'usdt_amount' => $this->usdt_amount,
            'addtime' => $this->addtime,
            'status' => (int) $this->status,
            'status_label' => $this->statusLabel(),
        ];
    }

    private function convertRateLabel(): string
    {
        $rates = [];

        if ((float) $this->from_rate_usdt !== 1.0) {
            $rates[] = strtoupper((string) $this->from_coin) . ': ' . $this->from_rate_usdt;
        }

        if ((float) $this->to_rate_usdt !== 1.0) {
            $rates[] = strtoupper((string) $this->to_coin) . ': ' . $this->to_rate_usdt;
        }

        return $rates === [] ? '-' : implode(' | ', $rates);
    }

    private function statusLabel(): string
    {
        return match ((int) $this->status) {
            1 => 'Completed',
            0 => 'Pending',
            2 => 'Cancelled',
            default => 'Unknown',
        };
    }
}
