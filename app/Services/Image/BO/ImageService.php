<?php

namespace App\Services\Image\BO;

use App\Http\Dto\Image\CreateImageDto;
use App\Models\Image;
use App\Models\Part;
use App\Resources\Image\BO\ImageCollection;
use App\Services\BasicService;
use Illuminate\Support\Facades\Storage;

class ImageService extends BasicService
{
    /**
     * Return main photo.
     *
     * @return ImageCollection
     */
    public function index(): ImageCollection
    {
        $queryBuilder = Image::query()->with('part');

        return new ImageCollection($queryBuilder->customPaginate(config('settings.pagination.perPage')));
    }

    /**
     * Store a new image.
     *
     * @param CreateImageDto $dto
     *
     * @return void
     */
    public function store(CreateImageDto $dto): void
    {
        \DB::beginTransaction();

        try {
            $part = Part::where('code', $dto->code)->first();

            $part->image()->delete();

            $fileName = $dto->file->getClientOriginalName();

            Storage::disk('public')->put($fileName, file_get_contents($dto->file));

            $part->image()->create([
                'url' => $fileName,
                'name' => $fileName,
            ]);

            \DB::commit();
        } catch (\Throwable $e) {
            $this->rollBackThrow($e);
        }
    }
}
