<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SiteConfigResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'webname' => $this->webname,
            'webtitle' => $this->webtitle,
            'bank_name' => $this->bank_name,
            'bank_acc_no' => $this->bank_acc_no,
            'bank_acc_name' => $this->bank_acc_name,
            'weblogo' => $this->weblogo,
            'waplogo' => $this->waplogo,
            'websildea' => $this->websildea,
            'websildeb' => $this->websildeb,
            'websildec' => $this->websildec,
            'wapsilded' => $this->wapsilded,
            'webissue' => $this->webissue,
            'webkj' => $this->webkj,
            'wapsildea' => $this->wapsildea,
            'wapsildeb' => $this->wapsildeb,
            'wapsildec' => $this->wapsildec,
            'wapissue' => $this->wapissue,
            'wapkj' => $this->wapkj,
            'webtjimgs' => $this->webtjimgs,
            'waptjimgs' => $this->waptjimgs,
            'webswitch' => (int) $this->webswitch,
        ];
    }
}
