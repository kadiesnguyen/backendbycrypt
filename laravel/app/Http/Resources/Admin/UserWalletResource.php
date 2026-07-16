<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserWalletResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'userid' => (int) $this->userid,
            'username' => $this->username ?? null,
            'coinname' => $this->coinname,
            'name' => $this->name,
            'remark' => $this->remark,
            'czline' => $this->czline,
            'addr' => $this->addr,
            'sort' => (int) $this->sort,
            'addtime' => $this->addtime,
            'status' => (int) $this->status,
            'status_label' => (int) $this->status === 1 ? 'Active' : 'Disabled',
        ];
    }
}
