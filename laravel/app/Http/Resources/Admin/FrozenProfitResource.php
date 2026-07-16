<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FrozenProfitResource extends JsonResource
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
            'num' => $this->num,
            'coin' => $this->coin,
            'status' => (int) $this->status,
            'status_label' => (int) $this->status === 1 ? 'Frozen' : 'Released',
            'addtime' => $this->addtime,
            'addday' => $this->addday,
            'thawtime' => $this->thawtime,
            'thawday' => $this->thawday,
            'remark' => $this->remark,
        ];
    }
}
