<?php

namespace App\Resources\Promotion\BO;

use App\Resources\BasicResource;

class PromotionResource extends BasicResource
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
