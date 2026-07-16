<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'coverImage' => $this->coverImage,
            'content' => $this->content,
            'status' => (int) $this->status,
            'status_label' => (int) $this->status === 1 ? 'Display' : 'Hidden',
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
