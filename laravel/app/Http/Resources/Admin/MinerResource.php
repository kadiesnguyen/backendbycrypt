<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MinerResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => (int) $this->type,
            'rtype' => (int) $this->rtype,
            'sharebl' => $this->sharebl,
            'sharecode' => $this->sharecode,
            'title' => $this->title,
            'content' => $this->content,
            'imgs' => $this->imgs,
            'outtype' => (int) $this->outtype,
            'dayoutnum' => $this->dayoutnum,
            'outcoin' => $this->outcoin,
            'pricenum' => $this->pricenum,
            'pricecoin' => $this->pricecoin,
            'cycle' => (int) $this->cycle,
            'suanl' => $this->suanl,
            'allnum' => (int) $this->allnum,
            'ycnum' => (int) $this->ycnum,
            'sellnum' => (int) $this->sellnum,
            'jlnum' => $this->jlnum,
            'jlcoin' => $this->jlcoin,
            'buyask' => (int) $this->buyask,
            'asknum' => (int) $this->asknum,
            'djout' => (int) $this->djout,
            'djday' => (int) $this->djday,
            'status' => (int) $this->status,
            'status_label' => (int) $this->status === 1 ? 'Active' : 'Suspended',
            'buymax' => (int) $this->buymax,
            'addtime' => $this->addtime,
        ];
    }
}
