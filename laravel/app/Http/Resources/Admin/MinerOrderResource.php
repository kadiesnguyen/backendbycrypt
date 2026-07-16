<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MinerOrderResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'kid' => $this->kid,
            'sharbltxt' => $this->sharbltxt,
            'type' => (int) $this->type,
            'sharebl' => $this->sharebl,
            'uid' => (int) $this->uid,
            'username' => $this->username,
            'kjtitle' => $this->kjtitle,
            'imgs' => $this->imgs,
            'status' => (int) $this->status,
            'status_label' => match ((int) $this->status) {
                1 => 'Active',
                2 => 'Suspended',
                3 => 'Expired',
                default => 'Unknown',
            },
            'cycle' => $this->cycle,
            'synum' => $this->synum,
            'outtype' => $this->outtype,
            'outcoin' => $this->outcoin,
            'outnum' => $this->outnum,
            'outusdt' => $this->outusdt,
            'djout' => $this->djout,
            'djnum' => $this->djnum,
            'addtime' => $this->addtime,
            'endtime' => $this->endtime,
            'intaddtime' => $this->intaddtime,
            'intendtime' => $this->intendtime,
            'last_earning_at' => $this->last_earning_at,
        ];
    }
}
