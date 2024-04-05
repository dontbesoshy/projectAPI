<?php

namespace App\Services\User\AD;

use App\Http\Dto\User\AD\NewPasswordDto;
use App\Models\User\User;
use App\Services\BasicService;

class UserService extends BasicService
{
    /**
     * Set a new password for the user.
     *
     * @param User $user
     * @param NewPasswordDto $dto
     *
     * @return void
     */
    public function setNewPassword(User $user, NewPasswordDto $dto): void
    {
        $user->update([
            'password' => bcrypt($dto->password),
        ]);
    }
}
