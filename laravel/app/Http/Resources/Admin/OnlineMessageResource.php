<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OnlineMessageResource extends JsonResource
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
            'type' => (int) $this->type,
            'content' => $this->content,
            'addtime' => $this->addtime,
            'state' => (int) $this->state,
        ];
    }
}
