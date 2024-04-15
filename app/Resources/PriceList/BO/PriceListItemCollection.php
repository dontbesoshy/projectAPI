<?php

namespace App\Resources\PriceList\BO;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PriceListItemCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param Request $request
     *
     * @return array
     */
    public function toArray($request): array
    {
        return parent::toArray($request);
    }
}
