<?php

namespace App\Services\User\BO;

use App\Enums\User\UserTypeEnum;
use App\Http\Dto\User\BO\CreateUserDto;
use App\Http\Dto\User\BO\FavoritePartsDto;
use App\Http\Dto\User\BO\NewLoginDto;
use App\Http\Dto\User\BO\NewPasswordDto;
use App\Models\User\User;
use App\Resources\User\BO\PartCollection;
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
        $users = User::query()
            ->with('priceLists')
            ->where('type', '=', UserTypeEnum::CLIENT)
            ->orderBy('company_name')
            ->get();

        return new UserCollection($users);
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
     * Set new password.
     *
     * @param User $user
     * @param NewLoginDto $dto
     *
     * @return void
     */
    public function setNewLogin(User $user, NewLoginDto $dto): void
    {
        $user->update([
            'login' => $dto->newLogin
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
        $user->priceLists()->detach();
        $user->delete();
    }

    /**
     * Clear counter login.
     *
     * @return void
     */
    public function clearCounterLogin(): void
    {
        User::query()->update([
            'login_counter' => 0
        ]);
    }

    /**
     * Get favorite parts.
     *
     * @param User $user
     *
     * @return PartCollection
     */
    public function getFavoriteParts(User $user): PartCollection
    {
        return new PartCollection($user->favoriteParts);
    }

    /**
     * Sync favorite parts.
     *
     * @param User $user
     * @param FavoritePartsDto $dto
     *
     * @return void
     */
    public function syncFavoriteParts(User $user, FavoritePartsDto $dto): void
    {
        $user->favoriteParts()->delete();

        foreach ($dto->partIds as $favoritePart) {
            $user->favoriteParts()->create([
                'part_id' => $favoritePart,
            ]);
        }
    }
}
