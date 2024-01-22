<?php

namespace App\Services\User;

use App\Http\Dto\User\CreateUserDto;
use App\Models\User;
use App\Resources\User\UserCollection;
use App\Services\BasicService;
use Illuminate\Support\Facades\DB;

class UserService extends BasicService
{
    /**
     * Return all users.
     *
     * @return UserCollection
     */
    public function index(): UserCollection
    {
        $queryBuilder = User::latest();

        return new UserCollection($queryBuilder->customPaginate(config('settings.pagination.perPage')));
    }

    /**
     * Create a new user.
     *
     * @param CreateUserDto $dto
     *
     * @return User
     */
    public function create(CreateUserDto $dto): User
    {
        DB::beginTransaction();

        try {
            $user = User::query()->create([
                'email' => $dto->email,
                'password' => $dto->password,
                'name' => $dto->name,
            ]);

            DB::commit();
        } catch (\Throwable $th) {
            $this->rollBackThrow($th);
        }

        return $user;
    }
}
