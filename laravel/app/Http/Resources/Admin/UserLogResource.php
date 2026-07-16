<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserLogResource extends JsonResource
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
            'type' => $this->type,
            'remark' => $this->remark,
            'addip' => $this->addip,
            'addr' => $this->addr,
            'sort' => (int) $this->sort,
            'addtime' => $this->addtime,
            'endtime' => $this->endtime,
            'status' => (int) $this->status,
            'status_label' => match ((int) $this->status) {
                0 => 'Disabled',
                1 => 'Active',
                2 => 'Revoked',
                default => 'Unknown',
            },
        ];
    }
}
