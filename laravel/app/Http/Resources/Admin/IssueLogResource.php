<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IssueLogResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'pid' => (int) $this->pid,
            'uid' => (int) $this->uid,
            'account' => $this->account,
            'name' => $this->name,
            'num' => $this->num,
            'open' => (int) $this->open,
            'percent' => $this->percent,
            'addtime' => $this->addtime,
            'endtime' => $this->endtime,
            'endday' => $this->endday,
            'status' => (int) $this->status,
            'status_label' => (int) $this->status === 1 ? 'Freezing' : 'Unfrozen',
        ];
    }
}
