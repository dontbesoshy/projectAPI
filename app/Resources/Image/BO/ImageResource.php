<?php

namespace App\Resources\Image\BO;

use App\Resources\BasicResource;
use App\Resources\PriceList\BO\PriceListItemResource;

class ImageResource extends BasicResource
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
            'part' => new PriceListItemResource($this->part),
        ];
    }
}
