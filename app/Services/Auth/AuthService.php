<?php

namespace App\Services\Auth;

use App\Exceptions\Auth\UsernameOrPasswordNotValidException;
use App\Http\Dto\User\LoginUserDto;
use App\Models\User;
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