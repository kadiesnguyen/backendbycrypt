<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserCoinResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'userid' => (int) $this->userid,
            'username' => $this->username ?? null,
            'usdt' => $this->usdt,
            'usdt_d' => $this->usdt_d,
            'btc' => $this->btc,
            'btc_d' => $this->btc_d,
            'eth' => $this->eth,
            'eth_d' => $this->eth_d,
            'ltc' => $this->ltc,
            'ltc_d' => $this->ltc_d,
            'sol' => $this->sol,
            'sol_d' => $this->sol_d,
            'xrp' => $this->xrp,
            'xrp_d' => $this->xrp_d,
            'uni' => $this->uni,
            'uni_d' => $this->uni_d,
            'xau' => $this->xau,
            'xau_d' => $this->xau_d,
            'bch' => $this->bch,
            'bch_d' => $this->bch_d,
            'dot' => $this->dot,
            'dot_d' => $this->dot_d,
            'trb' => $this->trb,
            'trb_d' => $this->trb_d,
            'trx' => $this->trx,
            'trx_d' => $this->trx_d,
            'trump' => $this->trump,
            'trump_d' => $this->trump_d,
        ];
    }
}
