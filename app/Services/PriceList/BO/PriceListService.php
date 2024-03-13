<?php

namespace App\Services\PriceList\BO;

use App\Http\Dto\File\UploadedFileDto;
use App\Http\Dto\PriceList\UpdatePriceListDto;
use App\Models\Part;
use App\Models\PriceList;
use App\Models\User\User;
use App\Resources\PriceList\AD\PriceListResource;
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
     * Show price list.
     *
     * @param PriceList $priceList
     *
     * @return PriceListResource
     */
    public function show(PriceList $priceList): PriceListResource
    {
        $priceList->load('parts');

        return new PriceListResource($priceList);
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

            $priceList = PriceList::query()->with('parts')->updateOrCreate(['name' => $fileName]);

            $parts = collect($reader)
                ->mapWithKeys(fn ($row, $key) => [$key => [
                    'price_list_id' => $priceList->id,
                    'ean' => $row[0],
                    'name' => $row[1],
                    'code' => $row[2],
                    'price' => $row[3],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]])
                ->toArray();

            Part::query()->where('price_list_id', $priceList->id)->delete();
            Part::insert($parts);

            DB::commit();
        } catch (\Throwable $e) {
            $this->rollBackThrow($e);
        }
    }

    /**
     * Upload price list.
     *
     * @param PriceList $priceList
     * @param UpdatePriceListDto $dto
     *
     * @return void
     */
    public function update(PriceList $priceList, UpdatePriceListDto $dto): void
    {
        DB::beginTransaction();

        try {
            Part::query()
                ->whereIn('id', collect($dto->parts)->pluck('id'))
                ->each(function (Part $part) use ($dto) {
                    $partFromDto = $dto->parts->first(fn ($partDto) => $partDto->id === $part->id);
                    $part->update([
                        'ean' => $partFromDto->ean,
                        'name' => $partFromDto->name,
                        'code' => $partFromDto->code,
                        'price' => $partFromDto->price,
                    ]);
                });

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
