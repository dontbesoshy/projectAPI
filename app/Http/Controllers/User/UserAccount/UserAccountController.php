<?php

namespace App\Http\Controllers\User\UserAccount;

use App\Http\Controllers\Controller;
use App\Services\User\UserAccount\UserAccountService;
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
     * Verify user email by token.
     *
     * @param string $token
     *
     * @return JsonResponse
     */
    public function verifyUserEmail(string $token): JsonResponse
    {
        $this->userAccountService->verifyUserEmail($token);
        return $this->OK();
    }
}
