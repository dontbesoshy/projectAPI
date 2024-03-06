<?php

namespace App\Services\PriceList\BO;

use App\Enums\User\UserTypeEnum;
use App\Http\Dto\File\UploadedFileDto;
use App\Http\Dto\User\BO\CreateUserDto;
use App\Models\PriceList;
use App\Models\User\User;
use App\Resources\User\BO\UserCollection;
use App\Services\BasicService;
use Aspera\Spreadsheet\XLSX\Reader;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PriceListService extends BasicService
{
    /**
     * Return all users.
     *
     * @return UserCollection
     */
    public function index(): UserCollection
    {
        $queryBuilder = User::query()->where('type', '=', UserTypeEnum::CLIENT);

        return new UserCollection($queryBuilder->customPaginate(config('settings.pagination.perPage')));
    }

    /**
     * Store a new price list.
     *
     * @param UploadedFileDto $dto
     *
     * @return void
     */
    public function create(UploadedFileDto $dto): void
    {
        DB::beginTransaction();

        try {
            $fileName = $dto->file->getClientOriginalName();

            Storage::disk('local')->put($fileName, file_get_contents($dto->file));

            $reader = new Reader();
            $reader->open(Storage::disk('local')->path($fileName));

            PriceList::query()->create([
                'name' => $fileName,
                'data' => collect($reader)->toJson()
            ]);

            DB::commit();
        } catch (\Throwable $e) {
            $this->rollBackThrow($e);
        }
    }

    /**
     * Attach user to price list.
     *
     * @param User $user
     * @param PriceList $priceList
     *
     * @return void
     */
    public function attachUser(User $user, PriceList $priceList): void
    {
        $user->priceLists()->sync($priceList);
    }
}
