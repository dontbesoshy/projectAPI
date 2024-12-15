<?php

namespace App\Resources\User\AD;

use App\Resources\BasicResource;

class PartResource extends BasicResource
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
            'image' => $this?->image,
        ];
    }
}
