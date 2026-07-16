<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'img' => $this->img,
            'content' => $this->content,
            'status' => (int) $this->status,
            'status_label' => (int) $this->status === 1 ? 'Display' : 'Hidden',
            'addtime' => $this->addtime,
        ];
    }
}
