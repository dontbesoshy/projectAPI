<?php

namespace App\Resources\Config;

use App\Resources\BasicResource;

class ConfigResource extends BasicResource
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
            'value' => $this->value,
            'type' => $this->type,
            'active' => $this->active,
        ];
    }
}
