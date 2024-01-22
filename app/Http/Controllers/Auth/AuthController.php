<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Dto\User\LoginUserDto;
use App\Services\Auth\AuthService;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    /**
     * Authenticate.
     *
     * @param LoginUserDto $request
     * @param AuthService $authService
     *
     * @return JsonResponse
     */
    public function authenticate(LoginUserDto $request, AuthService $authService): JsonResponse
    {
        return $this->OK($authService->authenticate($request));
    }

    /**
     * Logout.
     *
     * @param AuthService $authService
     *
     * @return JsonResponse
     */
    public function logout(AuthService $authService): JsonResponse
    {
        $authService->logout(auth()->user());
        return $this->OK();
    }
}
