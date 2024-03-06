<?php

namespace App\Services\User\BO;

use App\Enums\User\UserTypeEnum;
use App\Http\Dto\User\BO\CreateUserDto;
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
        $queryBuilder = User::query()->with('priceLists')->where('type', '=', UserTypeEnum::CLIENT);

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
                'company_address' => $dto->companyAddress,
                'type' => UserTypeEnum::CLIENT
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
