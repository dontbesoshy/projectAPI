<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Auth\Events\Registered;

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
        event(new Registered($user));
    }
}
