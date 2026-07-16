<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MiningPoolResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'oretitle' => $this->oretitle,
            'oreimg' => $this->oreimg,
            'summoney' => $this->summoney,
            'fmoney' => $this->fmoney,
            'minmoney' => $this->minmoney,
            'maxmoney' => $this->maxmoney,
            'coinname' => $this->coinname,
            'cc_coin' => $this->cc_coin,
            'rtype' => (int) $this->rtype,
            'rtype_label' => match ((int) $this->rtype) {
                1 => 'Fixed rate',
                2 => 'Fixed amount',
                3 => 'Fixed amount and rate',
                default => 'Unknown',
            },
            'status' => (int) $this->status,
            'status_label' => (int) $this->status === 1 ? 'Active' : 'Disabled',
            'addtime' => $this->addtime,
            'buytype' => (int) $this->buytype,
            'arrmoney' => $this->arrmoney,
            'buynum' => $this->buynum,
            'rway' => (int) $this->rway,
            'rway_label' => (int) $this->rway === 1 ? 'Automatic' : 'Manual',
            'sfbl' => $this->sfbl,
            'gdnum' => $this->gdnum,
            'gdbl' => $this->gdbl,
            'sort' => (int) $this->sort,
            'allmoney' => $this->allmoney,
        ];
    }
}
