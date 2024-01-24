<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\User\UserAccountService;
use Illuminate\Http\JsonResponse;

class UserAccountController extends Controller
{
    /**
     * UserController constructor.
     *
     * @param UserAccountService $userAccountService
     */
    public function __construct(private readonly UserAccountService $userAccountService)
    {
    }

    /**
     * Verify user by email.
     *
     * @param string $token
     *
     * @return JsonResponse
     */
    public function verifyRegisterToken(string $token): JsonResponse
    {
        $this->userAccountService->verifyRegisterToken($token);
        return $this->OK();
    }
}
