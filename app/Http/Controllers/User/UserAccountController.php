<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Dto\User\PO\EmailDto;
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

    /**
     * Forgot password.
     *
     * @param EmailDto $email
     *
     * @return JsonResponse
     */
    public function forgotPassword(EmailDto $email): JsonResponse
    {
        $this->userAccountService->forgotPassword($email);
        return $this->OK();
    }
}
