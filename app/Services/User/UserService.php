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
        $users = User::all();

        return new UserCollection($users);
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
            ]);

            DB::commit();
        } catch (\Throwable $th) {
            $this->rollBackThrow($th);
        }

        return $user;
    }
}
