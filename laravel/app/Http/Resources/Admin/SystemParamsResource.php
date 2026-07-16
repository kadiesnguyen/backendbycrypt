<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SystemParamsResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'kefu' => $this->kefu,
            'appeal' => $this->appeal,
            'smsemail' => $this->smsemail,
            'emailcode' => $this->emailcode,
            'smstemple' => $this->smstemple,
            'tgtext' => $this->tgtext,
            'gfemail' => $this->gfemail,
            'footertext' => $this->footertext,
            'telegram' => $this->telegram,
            'tymoney' => $this->tymoney,
            'regswitch' => (int) $this->regswitch,
            'tbswitch' => (int) $this->tbswitch,
            'regjl' => (int) $this->regjl,
            'checkin_rewards' => $this->checkin_rewards,
            'checkin_notify_status' => (int) ($this->checkin_notify_status ?? 1),
            'checkin_notify' => $this->checkin_notify,
        ];
    }
}
