<?php

namespace App\Resources\News\BO;

use App\Resources\BasicResource;

class NewsResource extends BasicResource
{
    /**
     * @param $request
     *
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'url' => $this->url,
            'active' => $this->active,
        ];
    }
}
