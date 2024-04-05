<?php

namespace App\Http\Controllers\User\AD;

use App\Http\Controllers\Controller;
use App\Http\Dto\User\AD\NewPasswordDto;
use App\Models\User\User;
use App\Services\User\AD\UserService;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    /**
     * UserController constructor.
     *
     * @param UserService $userService
     */
    public function __construct(private readonly UserService $userService)
    {
    }

    /**
     * Store a new user.
     *
     * @param User $user
     * @param NewPasswordDto $dto
     *
     * @return JsonResponse
     */
    public function setNewPassword(User $user, NewPasswordDto $dto): JsonResponse
    {
        $this->userService->setNewPassword($user, $dto);
        return $this->OK();
    }
}
