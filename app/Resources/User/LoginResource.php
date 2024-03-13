<?php

namespace App\Resources\User;

use App\Resources\BasicResource;
use Illuminate\Http\Request;

class LoginResource extends BasicResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'user' => [
                'id' => $this['user']->id,
                'isAdmin' => $this['user']->isAdmin(),
            ],
            'token' => $this['token'],
        ];
    }
}
