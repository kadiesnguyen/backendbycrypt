<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FrontendNavigationResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'lang' => $this->lang,
            'name' => $this->name,
            'title' => $this->title,
            'url' => $this->url,
            'sort' => (int) $this->sort,
            'addtime' => (int) $this->addtime,
            'endtime' => (int) $this->endtime,
            'status' => (int) $this->status,
            'get_login' => (bool) $this->get_login,
            'access' => (bool) $this->access,
        ];
    }
}
