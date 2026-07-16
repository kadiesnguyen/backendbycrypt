<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlatformMarketResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'round' => $this->round,
            'round_mum' => $this->round_mum ?? null,
            'fee_buy' => $this->fee_buy ?? null,
            'fee_sell' => $this->fee_sell ?? null,
            'buy_min' => $this->buy_min,
            'buy_max' => $this->buy_max,
            'sell_min' => $this->sell_min,
            'sell_max' => $this->sell_max,
            'trade_min' => $this->trade_min,
            'trade_max' => $this->trade_max,
            'trade_buy_num_min' => $this->trade_buy_num_min,
            'trade_buy_num_max' => $this->trade_buy_num_max,
            'trade_sell_num_min' => $this->trade_sell_num_min,
            'trade_sell_num_max' => $this->trade_sell_num_max,
            'invit_1' => $this->invit_1 ?? null,
            'invit_2' => $this->invit_2 ?? null,
            'invit_3' => $this->invit_3 ?? null,
            'invit_buy' => $this->invit_buy ?? null,
            'invit_sell' => $this->invit_sell ?? null,
            'zhang' => $this->zhang,
            'die' => $this->die,
            'hou_price' => $this->hou_price ?? null,
            'faxingjia' => $this->faxingjia ?? null,
            'new_price' => $this->new_price,
            'buy_price' => $this->buy_price,
            'sell_price' => $this->sell_price,
            'min_price' => $this->min_price,
            'max_price' => $this->max_price,
            'volume' => $this->volume,
            'change' => $this->change,
            'trade' => $this->trade,
            'status' => $this->status,
            'sort' => $this->sort,
            'jiaoyiqu' => $this->jiaoyiqu ?? null,
            'shuadan' => $this->shuadan,
            'sdtype' => $this->sdtype ?? null,
            'sdhigh' => $this->sdhigh,
            'sdlow' => $this->sdlow,
            'sdhigh_num' => $this->sdhigh_num ?? null,
            'sdlow_num' => $this->sdlow_num ?? null,
            'start_time' => $this->start_time ?? null,
            'start_minute' => $this->start_minute ?? null,
            'stop_time' => $this->stop_time ?? null,
            'stop_minute' => $this->stop_minute ?? null,
            'agree6' => $this->agree6 ?? null,
            'agree7' => $this->agree7 ?? null,
            'addtime' => $this->addtime,
            'endtime' => $this->endtime,
        ];
    }
}
