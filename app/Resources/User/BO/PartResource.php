<?php

namespace App\Resources\User\BO;

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
            'id' => $this->part->id,
            'ean' => $this->part->ean,
            'name' => $this->part->name,
            'code' => $this->part->code,
            'price' => $this->part->price,
        ];
    }
}
