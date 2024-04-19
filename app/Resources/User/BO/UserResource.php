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
            'login' => $this->login,
            'companyName' => $this->company_name,
            'loginCounter' => $this->login_counter,
            'priceList' => $this->priceLists->first()?->name
        ];
    }
}
