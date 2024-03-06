<?php

namespace App\Resources\User\BO;

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
            'password' => $this->password,
            'companyName' => $this->company_name,
            'companyAddress' => $this->company_address,
        ];
    }
}
