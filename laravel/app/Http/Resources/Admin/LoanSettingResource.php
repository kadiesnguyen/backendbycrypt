<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoanSettingResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'enabled' => (bool) $this->enabled,
            'min_amount' => (string) $this->min_amount,
            'max_amount' => (string) $this->max_amount,
            'duration_days' => (int) $this->duration_days,
            'daily_interest_rate' => (string) $this->daily_interest_rate,
            'lender_name' => $this->lender_name,
            'updated_at' => optional($this->updated_at)?->format('Y-m-d H:i:s'),
        ];
    }
}
