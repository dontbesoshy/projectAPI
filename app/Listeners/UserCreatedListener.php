<?php

namespace App\Listeners;

use App\Notifications\SendEmailToUserWithTokenNotification;

class UserCreatedListener
{
    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        $user = $event->user;

        $user->notify(new SendEmailToUserWithTokenNotification($user));
    }
}
