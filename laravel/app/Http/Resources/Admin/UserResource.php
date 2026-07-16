<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'fullname' => $this->fullname,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'cccd' => $this->cccd,
            'phonenumber' => $this->phonenumber,
            'gender' => $this->gender,
            'dob' => $this->dob,
            'country' => $this->country,
            'rzstatus' => (int) $this->rzstatus,
            'level' => (int) $this->level,
            'invit' => $this->invit,
            'invit_1' => $this->invit_1,
            'invit_2' => $this->invit_2,
            'invit_3' => $this->invit_3,
            'invit_1_username' => $this->when(isset($this->invit_1_username), $this->invit_1_username),
            'invit_2_username' => $this->when(isset($this->invit_2_username), $this->invit_2_username),
            'invit_3_username' => $this->when(isset($this->invit_3_username), $this->invit_3_username),
            'logins' => (int) $this->logins,
            'addip' => $this->addip,
            'addr' => $this->addr,
            'addtime' => (int) $this->addtime,
            'lgtime' => $this->lgtime,
            'loginip' => $this->loginip,
            'loginaddr' => $this->loginaddr,
            'logintime' => $this->logintime,
            'rztime' => (int) $this->rztime,
            'rzuptime' => (int) $this->rzuptime,
            'status' => (int) $this->status,
            'wdstatus' => $this->wdstatus !== null ? (int) $this->wdstatus : null,
            'txstate' => (int) $this->txstate,
            'trade_locked' => (int) ($this->trade_locked ?? 0),
            'trade_lock_msg' => $this->trade_lock_msg,
            'stoptime' => (int) $this->stoptime,
            'is_agent' => (int) $this->is_agent,
            'is_manager' => $this->when(isset($this->is_manager), (bool) $this->is_manager),
            'login_state' => $this->when(isset($this->login_state), $this->login_state),
            'bank_name' => $this->bank_name,
            'bank_acc_no' => $this->bank_acc_no,
            'bank_acc_name' => $this->bank_acc_name,
            'wallet' => $this->wallet,
            'money' => $this->money,
            'hy_result_mode' => (int) ($this->hy_result_mode ?? 0),
            'kefu' => $this->kefu,
            'cardzm' => $this->cardzm,
            'cardfm' => $this->cardfm,
            'usdt' => $this->when(isset($this->user_coin), fn () => $this->user_coin->usdt ?? '0'),
            'btc' => $this->when(isset($this->user_coin), fn () => $this->user_coin->btc ?? '0'),
            'eth' => $this->when(isset($this->user_coin), fn () => $this->user_coin->eth ?? '0'),
        ];
    }
}
