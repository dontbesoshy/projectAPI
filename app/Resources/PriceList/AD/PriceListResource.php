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
            'parts' => new PriceListItemCollection($this->parts),
        ];
    }
}
