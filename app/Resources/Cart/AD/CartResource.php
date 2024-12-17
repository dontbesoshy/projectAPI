<?php

namespace App\Resources\Cart\AD;

use App\Resources\BasicResource;

class CartResource extends BasicResource
{
    /**
     * @param $request
     *
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->part_id,
            'code' => $this->code,
            'ean' => $this->ean,
            'name' => $this->name,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'isActive' => $this->part !== null
        ];
    }
}
