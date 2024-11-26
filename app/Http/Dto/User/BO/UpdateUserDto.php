<?php

namespace App\Http\Dto\User\BO;

use App\Http\Dto\BasicDto;
use Spatie\LaravelData\Attributes\Validation\Unique;

class UpdateUserDto extends BasicDto
{
    #[Unique('users', 'email')]
    public string $email;
    public string $companyName;
}
