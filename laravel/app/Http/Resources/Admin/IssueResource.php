<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IssueResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'min' => $this->min,
            'max' => $this->max,
            'open' => (int) $this->open,
            'percent' => $this->percent,
            'imgs' => $this->imgs,
            'content' => $this->content,
            'addtime' => $this->addtime,
            'status' => (int) $this->status,
            'status_label' => (int) $this->status === 1 ? 'Display' : 'Hidden',
            'state' => (int) $this->state,
            'state_label' => (int) $this->state === 1 ? 'Enabled' : 'Disabled',
        ];
    }
}
