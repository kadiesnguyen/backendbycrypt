<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AgentResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'addip' => $this->addip,
            'addtime' => (int) $this->addtime,
            'invit' => $this->invit,
            'is_agent' => (int) $this->is_agent,
            'one' => $this->when(isset($this->referral_one), (int) $this->referral_one),
            'two' => $this->when(isset($this->referral_two), (int) $this->referral_two),
            'three' => $this->when(isset($this->referral_three), (int) $this->referral_three),
            'all' => $this->when(isset($this->referral_all), (int) $this->referral_all),
        ];
    }
}
