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

            $partsFromExcel = collect($reader)
                ->mapWithKeys(fn ($row, $key) => [$key => [
                    'ean' => str_replace(' ', '', $row[0]),
                    'name' => $row[1],
                    'code' => str_replace(' ', '', $row[2]),
                    'price' => $row[3] === '' || $row[3] === null ? 0 : str_replace(' ', '', $row[3]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]])
                ->filter(
                    fn ($part) =>
                        $part['ean'] !== null
                        && $part['ean'] !== ''
                        && $part['name'] !== null
                        && $part['name'] !== ''
                        && $part['code'] !== null
                        && $part['code'] !== ''
                        && $part['price'] !== ''
                );

            $existingPriceList = PriceList::where('name', $fileName)->with('parts')->first();

            if ($existingPriceList) {
                $existingParts = $existingPriceList->parts()->get()->keyBy('ean');

                $excelParts = $partsFromExcel->keyBy('ean');

                $partsToUpdate = [];
                $partsToCreate = [];
                $partsToDelete = $existingParts->keys()->diff($excelParts->keys());

                foreach ($excelParts as $ean => $excelPart) {
                    if (isset($existingParts[$ean])) {
                        $existingPart = $existingParts[$ean];
                        if ($existingPart->price != $excelPart['price']) {
                            $partsToUpdate[] = [
                                'id' => $existingPart->id,
                                'price' => $excelPart['price'],
                            ];
                        }
                    } else {
                        $partsToCreate[] = [
                            'price_list_id' => $existingPriceList->id,
                            'ean' => $ean,
                            'name' => $excelPart['name'],
                            'code' => $excelPart['code'],
                            'price' => $excelPart['price'],
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }

                if (!empty($partsToUpdate)) {
                    foreach ($partsToUpdate as $updateData) {
                        DB::table('parts')
                            ->where('id', $updateData['id'])
                            ->update([
                                'price' => $updateData['price'],
                                'updated_at' => now(),
                            ]);
                    }
                }

                if (!$partsToDelete->isEmpty()) {
                    foreach ($partsToDelete as $partToDelete) {
                        $image = Image::query()->where('ean', $partToDelete)->first();

                        if ($image->parts()->count() === 1) {
                            $image->delete();
                        }

                        $existingPriceList->parts()->where('ean', $partToDelete)->delete();

                        foreach ($existingPriceList->users as $user) {
                            if ($user->cart) {
                                $user->cart->cartItems()->where('ean', $partToDelete)->delete();
                            }
                        }
                    }
                }

                if (!empty($partsToCreate)) {
                    foreach ($partsToCreate as $createData) {
                        $imageExists = Image::query()->where('ean', $createData['ean'])->first();

                        if (!$imageExists) {
                            DB::table('images')->insert([
                                'ean' => $createData['ean'],
                                'url' => $createData['ean'].'.jpg',
                                'name' => $createData['ean'].'.jpg',
                                'updated_at' => now(),
                                'created_at' => now(),
                            ]);
                        }
                    }

                    DB::table('parts')->insert($partsToCreate);
                }
            } else {
                $priceList = PriceList::create(['name' => $fileName, 'active' => true]);

                $existingEan = Image::query()
                    ->whereIn('ean', $partsFromExcel->pluck('ean'))
                    ->pluck('ean')
                    ->toArray();

                $imagesToCreate = $partsFromExcel->filter(function ($part) use ($existingEan) {
                    return !in_array($part['ean'], $existingEan);
                })->map(function ($part) {
                    return [
                        'url' => $part['code'] . '.jpg',
                        'name' => $part['code'] . '.jpg',
                        'ean' => $part['ean'],
                        'part_code' => $part['code'],
                        'updated_at' => now(),
                        'created_at' => now(),
                    ];
                });

                if ($imagesToCreate->isNotEmpty()) {
                    Image::query()->insert($imagesToCreate->toArray());
                }

                $priceList->parts()->createMany($partsFromExcel->toArray());
            }

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
        $priceList->parts()->delete();

        DB::table('price_list_user')
            ->where('price_list_id', $priceList->id)
            ->delete();

        $priceList->users()->each(function (User $user) {
            $user->cart->delete();
        });

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
