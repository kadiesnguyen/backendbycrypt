<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContractSettingResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'hy_sxf' => $this->hy_sxf,
            'hy_time' => $this->hy_time,
            'hy_ykbl' => $this->hy_ykbl,
            'hy_tzed' => $this->hy_tzed,
            'hy_min' => $this->hy_min,
            'hy_min_per_frame' => $this->hy_min_per_frame ?? null,
            'hy_max_per_frame' => $this->hy_max_per_frame ?? null,
            'hy_kstime' => $this->hy_kstime,
            'hy_ksid' => $this->hy_ksid,
            'hy_ylid' => $this->hy_ylid,
            'hy_fkgl' => $this->hy_fkgl,
            'checkin_rewards' => $this->checkin_rewards ?? null,
        ];
    }
}
