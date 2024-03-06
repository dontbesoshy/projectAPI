<?php

namespace App\Services\User\PO;

use App\Enums\User\UserTypeEnum;
use App\Http\Dto\User\PO\CreateUserDto;
use App\Models\User\User;
use App\Services\BasicService;
use Illuminate\Support\Facades\DB;

class UserService extends BasicService
{
    /**
     * Create a new user.
     *
     * @param CreateUserDto $dto
     *
     * @return string|null
     */
    public function create(CreateUserDto $dto): null|string
    {
        $token = null;

        DB::beginTransaction();

        try {
            $user = User::query()->create([
                'email' => $dto->email,
                'password' => $dto->password,
                'name' => $dto->name,
                'type' => UserTypeEnum::CLIENT,
            ]);

            $token = $user
                ->createToken('registration', expiresAt: now()->addHours(5))
                ->plainTextToken;

            DB::commit();
        } catch (\Throwable $th) {
            $this->rollBackThrow($th);
        }

        return $token;
    }
}
