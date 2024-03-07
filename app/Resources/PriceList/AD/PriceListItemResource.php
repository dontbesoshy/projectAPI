<?php

namespace App\Resources\PriceList\AD;

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
            'ean' => $this[0],
            'name' => $this[1],
            'kod' => $this[2],
            'price' => $this[3],
        ];
    }
}
