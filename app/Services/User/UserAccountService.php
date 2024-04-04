<?php

namespace App\Services\User;

use App\Exceptions\User\RegisterToken\RegisterTokenNotValidException;
use App\Exceptions\User\UserNotFoundException;
use App\Http\Dto\User\PO\EmailDto;
use App\Http\Dto\User\PO\LoginDto;
use App\Models\User\RegisterToken;
use App\Models\User\User;
use App\Notifications\User\PO\SendEmailToUserWithNewPasswordNotification;
use App\Notifications\User\PO\SendEmailToUserWithTokenNotification;
use App\Services\BasicService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\Concerns\Has;

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

    /**
     * Forgot password.
     *
     * @param LoginDto $dto
     *
     * @return void
     */
   public function forgotPassword(LoginDto $dto): void
   {
       $user = User::where('login', $dto->login)->first();

       $this->throwIf(
           !$user,
           UserNotFoundException::class
       );

       $newPassword = Str::random(8);

       $user->update([
           'password' => Hash::make($newPassword)
       ]);

       $user->notify(new SendEmailToUserWithNewPasswordNotification($user, $newPassword));
   }
}
