<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NoticeResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'uid' => (int) $this->uid,
            'account' => $this->account,
            'title' => $this->title,
            'content' => $this->content,
            'imgs' => $this->imgs,
            'addtime' => $this->addtime,
            'status' => (int) $this->status,
            'user_view' => (int) $this->user_view,
        ];
    }
}
