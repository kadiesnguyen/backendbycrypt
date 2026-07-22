<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoanResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => (int) $this->user_id,
            'username' => $this->username,
            'amount' => (string) $this->amount,
            'currency' => 'USDT',
            'duration_days' => (int) $this->duration_days,
            'daily_interest_rate' => (string) $this->daily_interest_rate,
            'lender_name' => $this->lender_name,
            'interest_amount' => (string) $this->interest_amount,
            'repay_amount' => (string) $this->repay_amount,
            'status' => $this->status,
            'status_label' => $this->statusLabel(),
            'note' => $this->note,
            'img_front' => $this->img_front,
            'img_back' => $this->img_back,
            'approved_at' => optional($this->approved_at)?->format('Y-m-d H:i:s'),
            'due_at' => optional($this->due_at)?->format('Y-m-d H:i:s'),
            'repaid_at' => optional($this->repaid_at)?->format('Y-m-d H:i:s'),
            'created_at' => optional($this->created_at)?->format('Y-m-d H:i:s'),
            'updated_at' => optional($this->updated_at)?->format('Y-m-d H:i:s'),
        ];
    }

    private function statusLabel(): string
    {
        return match ((string) $this->status) {
            'pending' => 'Pending approval',
            'rejected' => 'Rejected',
            'active' => 'Active',
            'repaid' => 'Repaid',
            'overdue' => 'Overdue',
            default => 'Unknown',
        };
    }
}
