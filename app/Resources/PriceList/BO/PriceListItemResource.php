<?php

namespace App\Resources\PriceList\BO;

use App\Resources\BasicResource;

class PriceListItemResource extends BasicResource
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
            'ean' => $this->ean,
            'name' => $this->name,
            'code' => $this->code,
            'price' => $this->price,
        ];
    }
}