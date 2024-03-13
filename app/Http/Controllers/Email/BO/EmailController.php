<?php

namespace App\Http\Controllers\Email\BO;

use App\Http\Controllers\Controller;
use App\Http\Dto\Email\BO\CreateEmailDto;
use App\Http\Dto\User\BO\CreateUserDto;
use App\Models\Email;
use App\Services\Email\BO\EmailService;
use App\Services\User\BO\UserService;
use Illuminate\Http\JsonResponse;

class EmailController extends Controller
{
    /**
     * EmailController constructor.
     *
     * @param EmailService $emailService
     */
    public function __construct(private readonly EmailService $emailService)
    {
    }

    /**
     * Return all emails.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->OK($this->emailService->index());
    }

    /**
     * Create a new email.
     *
     * @param CreateEmailDto $dto
     *
     * @return JsonResponse
     */
    public function store(CreateEmailDto $dto): JsonResponse
    {
        $this->emailService->create($dto);
        return $this->CREATED();
    }

    /**
     * Delete a email.
     *
     * @param Email $email
     *
     * @return JsonResponse
     */
    public function destroy(Email $email): JsonResponse
    {
        $this->emailService->delete($email);
        return $this->OK();
    }
}
