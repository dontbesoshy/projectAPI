<?php

namespace App\Resources\PriceList\AD;

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
            'data' => new PriceListItemCollection(collect($this->data)),
        ];
    }
}