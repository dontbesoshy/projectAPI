<?php

namespace App\Services\PriceList\BO;

use App\Http\Dto\File\UploadedFileDto;
use App\Models\PriceList;
use App\Models\User\User;
use App\Resources\PriceList\BO\PriceListCollection;
use App\Services\BasicService;
use Aspera\Spreadsheet\XLSX\Reader;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PriceListService extends BasicService
{
    /**
     * Return all price lists.
     *
     * @return PriceListCollection
     */
    public function index(): PriceListCollection
    {
        $queryBuilder = PriceList::query()->get();

        return new PriceListCollection($queryBuilder);
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
            $reader = new Reader();
            $fileName = $dto->file->getClientOriginalName();

            Storage::disk('local')->put($fileName, file_get_contents($dto->file));

            $reader->open(Storage::disk('local')->path($fileName));

            $priceList = PriceList::query()->updateOrCreate(['name' => $fileName]);

            $priceList->update(['data' => collect($reader)]);

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
