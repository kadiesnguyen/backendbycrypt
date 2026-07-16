<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DepositPortResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'wallet' => $this->wallet,
            'address' => $this->address,
            'coin' => $this->coin,
            'status' => (int) $this->status,
        ];
    }
}
