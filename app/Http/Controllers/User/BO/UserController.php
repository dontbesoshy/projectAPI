<?php

namespace App\Http\Controllers\User\BO;

use App\Http\Controllers\Controller;
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
}
