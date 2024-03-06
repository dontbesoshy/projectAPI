<?php

namespace App\Observers;

use App\Models\User\User;
use App\Services\User\UserAccountService;

class UserObserver
{
    /**
     * Created a new user.
     *
     * @param User $user
     *
     * @return void
     */
    public function created(User $user): void
    {
        app(UserAccountService::class)->sendRegistrationToken($user);
    }
}
