<?php

namespace App\Http\Dto\User;

use App\Http\Dto\BasicDto;
use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Max;

class LoginUserDto extends BasicDto
{
    #[Email]
    public string $email;

    #[Max(250)]
    public string $password;
}