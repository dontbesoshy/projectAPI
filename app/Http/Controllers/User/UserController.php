<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Dto\User\CreateUserDto;
use App\Services\User\UserService;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
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
     * Store a new user.
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
