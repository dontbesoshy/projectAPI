<?php

namespace App\Http\Dto\User\BO;

use App\Http\Dto\BasicDto;
use Spatie\LaravelData\Attributes\Validation\Same;

class NewPasswordDto extends BasicDto
{
    public string $password;
    #[Same('password')]
    public string $newPassword;
}
