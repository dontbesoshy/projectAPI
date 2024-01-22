<?php

namespace App\Services\User\UserAccount;

use App\Http\Dto\User\CreateUserDto;
use App\Models\User;
use App\Resources\User\UserCollection;
use App\Services\BasicService;
use Illuminate\Support\Facades\DB;

class UserAccountService extends BasicService
{
    /**
     * Verify user email by token.
     *
     * @param string $token
     *
     * @return string
     */
    public function verifyUserEmail(string $token): string
    {
        return $token;
   }
}
