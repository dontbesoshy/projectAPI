<?php

namespace App\Services\PriceList\BO;

use App\Http\Dto\File\UploadedFileDto;
use App\Http\Dto\PriceList\UpdatePriceListDto;
use App\Models\Image;
use App\Models\Part;
use App\Models\PriceList;
use App\Models\User\User;
use App\Resources\PriceList\BO\PriceListResource;
use App\Resources\PriceList\BO\PriceListShortCollection;
use App\Services\BasicService;
use Aspera\Spreadsheet\XLSX\Reader;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PriceListService extends BasicService
{
    /**
     * Return all price lists.
     *
     * @return PriceListShortCollection
     */
    public function index(): PriceListShortCollection
    {
        $queryBuilder = PriceList::query()->get();

        return new PriceListShortCollection($queryBuilder);
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
        $priceList->load('parts.image');

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

            $parts = collect($reader)
                ->mapWithKeys(fn ($row, $key) => [$key => [
                    'ean' => str_replace(' ', '', $row[0]),
                    'name' => $row[1],
                    'code' => str_replace(' ', '', $row[2]),
                    'price' => $row[3] === '' || $row[3] === null ? 0 : str_replace(' ', '', $row[3]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]])
                ->filter(fn ($part) => $part['ean'] !== null && $part['name'] !== '' && $part['code'] !== '' && $part['price'] !== '');

            $priceList = PriceList::where('name', $fileName)->first();

            if ($priceList) {
                Image::query()->whereIn('part_id', $priceList->parts->pluck('id'))->delete();
                $priceList->parts()->delete();
                $priceList->update(['active' => true]);
            } else {
                $priceList = PriceList::create(['name' => $fileName, 'active' => true]);
            }
            $priceList->parts()->createMany($parts->toArray());

            $priceList->parts()->each(function (Part $part) {
                $part->image()->create([
                    'part_code' => $part->code,
                    'url' => $part->code.'.jpg',
                    'name' => $part->code.'.jpg',
                ]);
            });

            $priceList->touch();

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
                ->where('price_list_id', $priceList->id)
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
     * Delete price list.
     *
     * @param PriceList $priceList
     *
     * @return void
     */
    public function delete(PriceList $priceList): void
    {
        Image::query()->whereIn('part_id', $priceList->parts->pluck('id'))->delete();

        $priceList->parts()->delete();

        DB::table('price_list_user')
            ->where('price_list_id', $priceList->id)
            ->delete();

        $priceList->delete();
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
