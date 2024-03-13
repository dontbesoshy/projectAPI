<?php

namespace App\Services\Email\BO;

use App\Enums\User\UserTypeEnum;
use App\Http\Dto\Email\BO\CreateEmailDto;
use App\Models\Email;
use App\Models\User\User;
use App\Resources\Email\BO\EmailCollection;
use App\Services\BasicService;
use Illuminate\Support\Facades\DB;

class EmailService extends BasicService
{
    /**
     * Return all emails.
     *
     * @return EmailCollection
     */
    public function index(): EmailCollection
    {
        $queryBuilder = Email::query();

        return new EmailCollection($queryBuilder->customPaginate(config('settings.pagination.perPage')));
    }

    /**
     * Create a new email.
     *
     * @param CreateEmailDto $dto
     *
     * @return void
     */
    public function create(CreateEmailDto $dto): void
    {
        Email::query()->updateOrCreate(['email' => $dto->email]);
    }

    /**
     * Delete a email.
     *
     * @param Email $email
     *
     * @return void
     */
    public function delete(Email $email): void
    {
        $email->delete();
    }
}
