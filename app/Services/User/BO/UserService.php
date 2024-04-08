<?php

namespace App\Services\User\BO;

use App\Enums\User\UserTypeEnum;
use App\Http\Dto\User\BO\CreateUserDto;
use App\Http\Dto\User\BO\NewPasswordDto;
use App\Models\User\User;
use App\Resources\User\BO\UserCollection;
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
        $queryBuilder = User::query()
            ->with('priceLists')
            ->where('type', '=', UserTypeEnum::CLIENT)
            ->orderBy('company_name');

        return new UserCollection($queryBuilder->customPaginate(config('settings.pagination.perPage')));
    }

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
                'company_name' => $dto->companyName,
                'type' => UserTypeEnum::CLIENT,
                'login' => $dto->login,
                'email_verified_at' => now(),
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

    /**
     * Set new password.
     *
     * @param User $user
     * @param NewPasswordDto $dto
     *
     * @return void
     */
    public function setNewPassword(User $user, NewPasswordDto $dto): void
    {
        $user->update([
            'password' => bcrypt($dto->newPassword)
        ]);
    }

    /**
     * Delete user.
     *
     * @param User $user
     *
     * @return void
     */
    public function delete(User $user): void
    {
        $user->registerToken()->forceDelete();
        $user->priceLists()->forceDelete();
        $user->delete();
    }
}
