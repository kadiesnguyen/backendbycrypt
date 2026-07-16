<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CtMarketResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'coinname' => $this->coinname,
            'name' => $this->name,
            'symbol' => $this->symbol,
            'title' => $this->title,
            'status' => (int) $this->status,
            'state' => (int) $this->state,
            'sort' => (int) $this->sort,
            'addtime' => $this->addtime,
            'logo' => $this->logo,
        ];
    }
}
