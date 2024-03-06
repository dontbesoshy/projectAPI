<?php

namespace App\Resources\User\AD;

use App\Resources\BasicResource;

class UserResource extends BasicResource
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
            'email' => $this->email,
        ];
    }
}
