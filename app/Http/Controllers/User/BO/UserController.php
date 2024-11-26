<?php

namespace App\Http\Controllers\User\BO;

use App\Http\Controllers\Controller;
use App\Http\Dto\User\BO\ClearLoginCounterDto;
use App\Http\Dto\User\BO\CreateUserDto;
use App\Http\Dto\User\BO\FavoritePartsDto;
use App\Http\Dto\User\BO\NewLoginDto;
use App\Http\Dto\User\BO\NewPasswordDto;
use App\Http\Dto\User\BO\UpdateUserDto;
use App\Models\User\User;
use App\Services\User\BO\UserService;
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
     * Return all users.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->OK($this->userService->index());
    }

    /**
     * Create a new user.
     *
     * @param CreateUserDto $dto
     *
     * @return JsonResponse
     */
    public function store(CreateUserDto $dto): JsonResponse
    {
        return $this->OK($this->userService->create($dto));
    }

    /**
     * Update user.
     *
     * @param User $user
     * @param UpdateUserDto $dto
     *
     * @return JsonResponse
     */
    public function update(User $user, UpdateUserDto $dto): JsonResponse
    {
        $this->userService->update($user, $dto);
        return $this->OK();
    }

    /**
     * Set new password.
     *
     * @param User $user
     * @param NewPasswordDto $request
     *
     * @return JsonResponse
     */
    public function setNewPassword(User $user, NewPasswordDto $request): JsonResponse
    {
        $this->userService->setNewPassword($user, $request);
        return $this->OK();
    }

    /**
     * Set new login.
     *
     * @param User $user
     * @param NewLoginDto $request
     *
     * @return JsonResponse
     */
    public function setNewLogin(User $user, NewLoginDto $request): JsonResponse
    {
        $this->userService->setNewLogin($user, $request);
        return $this->OK();
    }

    /**
     * Delete user.
     *
     * @param User $user
     *
     * @return JsonResponse
     */
    public function destroy(User $user): JsonResponse
    {
        $this->userService->delete($user);
        return $this->OK();
    }

    /**
     * Clear counter login.
     *
     * @param ClearLoginCounterDto $request
     *
     * @return JsonResponse
     */
    public function clearCounterLogin(ClearLoginCounterDto $request): JsonResponse
    {
        $this->userService->clearCounterLogin($request);
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
}
