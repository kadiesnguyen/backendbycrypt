<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminAccountResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'username' => $this->username,
            'nickname' => $this->nickname,
            'moble' => $this->moble,
            'sort' => (int) $this->sort,
            'addtime' => (int) $this->addtime,
            'last_login_time' => (int) $this->last_login_time,
            'last_login_ip' => (int) $this->last_login_ip,
            'endtime' => (int) $this->endtime,
            'status' => (int) $this->status,
            'level' => (int) $this->level,
            'auth_group' => $this->when(isset($this->auth_group), $this->auth_group),
        ];
    }
}
