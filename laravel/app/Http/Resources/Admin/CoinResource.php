<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CoinResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'title' => $this->title,
            'type' => (int) $this->type,
            'czline' => $this->czline,
            'czaddress' => $this->czaddress,
            'czstatus' => (int) $this->czstatus,
            'czminnum' => $this->czminnum,
            'txstatus' => (int) $this->txstatus,
            'txminnum' => $this->txminnum,
            'txmaxnum' => $this->txmaxnum,
            'sxftype' => (int) ($this->sxftype ?? 1),
            'txsxf' => $this->txsxf,
            'txsxf_n' => $this->txsxf_n,
            'bbsxf' => $this->bbsxf,
            'hysxf' => $this->hysxf,
            'bank' => $this->bank,
            'sort' => (int) $this->sort,
            'status' => (int) $this->status,
            'addtime' => $this->addtime,
        ];
    }
}
