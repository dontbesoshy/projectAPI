<?php

namespace App\Http\Dto\User\BO;

use App\Http\Dto\BasicDto;
use Spatie\LaravelData\Attributes\Validation\Same;

class NewLoginDto extends BasicDto
{
    public string $login;
    #[Same('login')]
    public string $newLogin;
}
