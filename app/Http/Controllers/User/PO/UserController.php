<?php

namespace App\Http\Controllers\User\PO;

use App\Http\Controllers\Controller;
use App\Http\Dto\User\PO\CreateUserDto;
use App\Services\User\PO\UserService;
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
     * @param CreateUserDto $dto
     *
     * @return JsonResponse
     */
    public function store(CreateUserDto $dto): JsonResponse
    {
        return $this->OK($this->userService->create($dto));
    }
}
