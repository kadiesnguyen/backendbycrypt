<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MinerProfitResource extends JsonResource
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
            'kid' => (int) $this->kid,
            'ktitle' => $this->ktitle,
            'num' => $this->num,
            'coin' => $this->coin,
            'addtime' => $this->addtime,
            'day' => $this->day,
        ];
    }
}
