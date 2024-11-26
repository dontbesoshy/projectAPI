<?php

namespace App\Http\Controllers\User\AD;

use App\Http\Controllers\Controller;
use App\Http\Dto\User\AD\FavoritePartsDto;
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

    /**
     * Sync favorite parts.
     *
     * @param User $user
     * @param FavoritePartsDto $request
     *
     * @return JsonResponse
     */
    public function syncFavoriteParts(User $user, FavoritePartsDto $request): JsonResponse
    {
        $this->userService->syncFavoriteParts($user, $request);
        return $this->OK();
    }

    /**
     * Get favorite parts.
     *
     * @param User $user
     *
     * @return JsonResponse
     */
    public function getFavoriteParts(User $user): JsonResponse
    {
        return $this->OK($this->userService->getFavoriteParts($user));
    }
}
