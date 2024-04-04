<?php

namespace App\Http\Dto\User\PO;

use App\Http\Dto\BasicDto;
use Spatie\LaravelData\Attributes\Validation\Unique;

class CreateUserDto extends BasicDto
{
    #[Unique('users', 'email')]
    public string $email;

    public string $password;

    public string $name;

    public string $login;
}
