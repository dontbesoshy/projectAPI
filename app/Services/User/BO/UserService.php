<?php

namespace App\Services\User\BO;

use App\Enums\User\UserTypeEnum;
use App\Exceptions\User\UserAlreadyExistsInThisEmailException;
use App\Http\Dto\User\BO\ClearLoginCounterDto;
use App\Http\Dto\User\BO\CreateUserDto;
use App\Http\Dto\User\BO\FavoritePartsDto;
use App\Http\Dto\User\BO\NewLoginDto;
use App\Http\Dto\User\BO\NewPasswordDto;
use App\Http\Dto\User\BO\UpdateUserDto;
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
     * Update user.
     *
     * @param User $user
     * @param UpdateUserDto $dto
     *
     * @return void
     */
    public function update(User $user, UpdateUserDto $dto): void
    {
        $userExists = User::query()
            ->where('id', '!=', $user->id)
            ->where(function ($query) use ($dto) {
                $query->where('email', $dto->email)
                    ->orWhere('login', $dto->login);
            })
            ->first();

        if ($userExists) {
            $this->throw(new UserAlreadyExistsInThisEmailException());
        }

        $user->update([
            'email' => $dto->email,
            'company_name' => $dto->companyName,
            'login' => $dto->login,
        ]);
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
     * @param ClearLoginCounterDto $dto
     *
     * @return void
     */
    public function clearCounterLogin(ClearLoginCounterDto $dto): void
    {
        if ($dto->userId) {
            User::query()->where('id', $dto->userId)->update([
                'login_counter' => 0
            ]);
        } else {
            User::query()->update([
                'login_counter' => 0
            ]);
        }
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
        if ($user->priceLists->isEmpty()) {
            return new PartCollection([]);
        }

        $parts = $user
            ->priceLists
            ->first()
            ->partsWithTrashed
            ->filter(fn($part) => in_array($part->ean, $user->favoriteParts->pluck('ean')->toArray()))
            ->groupBy('ean')
            ->map(function ($groupedParts) {
                return $groupedParts->first(fn($part) => !$part->trashed()) ?? $groupedParts->first();
            })
            ->values();

        return new PartCollection($parts);
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

        foreach ($dto->partEans as $partEan) {
            $user->favoriteParts()->create([
                'ean' => $partEan,
            ]);
        }
    }
}
