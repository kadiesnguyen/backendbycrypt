<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContractResultQueueResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $addtime = (int) $this->addtime;

        return [
            'id' => $this->id,
            'round_no' => (int) $this->round_no,
            'result' => $this->result,
            'addtime' => $addtime,
            'addtime_text' => $this->formatAddtime($addtime),
        ];
    }

    private function formatAddtime(int $timestamp): string
    {
        if ($timestamp <= 0) {
            return '---';
        }

        return date('Y-m-d H:i:s', $timestamp);
    }
}
