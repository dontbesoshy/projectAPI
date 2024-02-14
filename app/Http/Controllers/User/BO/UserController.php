<?php

namespace App\Http\Controllers\User\BO;

use App\Http\Controllers\Controller;
use App\Http\Dto\User\BO\CreateUserDto;
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
}
