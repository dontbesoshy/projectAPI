<?php

namespace App\Http\Dto\User\BO;

use App\Http\Dto\BasicDto;

class UpdateUserDto extends BasicDto
{
    public string $email;
    public string $companyName;
}
