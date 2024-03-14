<?php

namespace App\Services\Auth;

use App\Exceptions\Auth\UsernameOrPasswordNotValidException;
use App\Exceptions\User\UserNotVerifiedException;
use App\Http\Dto\User\LoginUserDto;
use App\Models\Part;
use App\Models\User\User;
use App\Resources\User\LoginResource;
use App\Services\BasicService;
use Illuminate\Support\Facades\Hash;

class AuthService extends BasicService
{
    /**
     * User login.
     *
     * @param LoginUserDto $request
     *
     * @return LoginResource
     */
    public function authenticate(LoginUserDto $request): LoginResource
    {
        $user = User::where('email', $request->email)->first();

        $this->throwIf(
            !$user ||!Hash::check($request->password, $user->password),
            UsernameOrPasswordNotValidException::class
        );

        $this->throwIf(
            $user->email_verified_at === null,
            UserNotVerifiedException::class
        );

        $token = $user->createToken('my-app-token')->plainTextToken;

        $loginArray = [
            'user' => $user,
            'token' => $token,
        ];

        return new LoginResource($loginArray);
    }

    /**
     * User logout.
     *
     * @param User $user
     *
     * @return void
     */
    public function logout(User $user): void
    {
        $user->currentAccessToken()->delete();
    }
}
