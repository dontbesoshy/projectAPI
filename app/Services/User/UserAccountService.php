<?php

namespace App\Services\User;

use App\Exceptions\User\RegisterToken\RegisterTokenNotValidException;
use App\Models\User\RegisterToken;
use App\Models\User\User;
use App\Notifications\User\PO\SendEmailToUserWithTokenNotification;
use App\Services\BasicService;
use Illuminate\Support\Str;

class UserAccountService extends BasicService
{
    /**
     * Send registration token to user.
     *
     * @param User $user
     *
     * @return void
     */
    public function sendRegistrationToken(User $user): void
    {
        if (!$user->registerToken) {
            $token = RegisterToken::create([
                'user_id' => $user->id,
                'token' => Str::uuid()
            ]);
        } else {
            $token = $user->registerToken;
        }

        $user->notify(new SendEmailToUserWithTokenNotification($user, $token));
    }

    /**
     * Verify user email by token.
     *
     * @param string $token
     *
     * @return void
     */
    public function verifyRegisterToken(string $token): void
    {
        $registerToken = RegisterToken::where('token', $token)->first();

        $this->throwIf(
            !$registerToken,
            RegisterTokenNotValidException::class
        );

        $user = $registerToken->user;

        $user->update([
            'email_verified_at' => now()
        ]);

        $registerToken->delete();
   }
}
