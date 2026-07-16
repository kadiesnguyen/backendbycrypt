<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BillResource extends JsonResource
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
            'coinname' => $this->coinname,
            'afternum' => $this->afternum,
            'type' => (int) $this->type,
            'st' => (int) $this->st,
            'st_label' => match ((int) $this->st) {
                1 => 'Income',
                2 => 'Expense',
                default => 'Other',
            },
            'remark' => $this->remark,
            'addtime' => $this->addtime,
        ];
    }
}
