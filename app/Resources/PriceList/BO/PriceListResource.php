<?php

namespace App\Resources\PriceList\BO;

use App\Resources\BasicResource;

class PriceListResource extends BasicResource
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
            'active' => $this->active,
            'createdAt' => $this->created_at,
        ];
    }
}
