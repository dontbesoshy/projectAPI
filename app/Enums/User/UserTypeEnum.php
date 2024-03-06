<?php

namespace App\Enums\User;

enum UserTypeEnum: string
{
    case ADMIN = 'admin';
    case CLIENT = 'client';
}
