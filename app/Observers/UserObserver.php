<?php

namespace App\Observers;

use App\Events\UserCreated;
use App\Models\User;

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
        event(new UserCreated($user));
    }
}
