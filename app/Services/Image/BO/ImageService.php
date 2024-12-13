<?php

namespace App\Services\Image\BO;

use App\Http\Dto\Image\FilesDto;
use App\Http\Dto\Image\ImageDto;
use App\Models\Image;
use App\Models\Part;
use App\Resources\Image\BO\ImageCollection;
use App\Services\BasicService;
use Illuminate\Support\Facades\DB;
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
        $images = Image::query()->get()->unique('part_code')->values()->sortBy('name');

        return new ImageCollection($images);
    }

    /**
     * Store a new image.
     *
     * @param FilesDto $dto
     *
     * @return void
     */
    public function store(FilesDto $dto): void
    {
        DB::beginTransaction();

        try {
            foreach ($dto->images as $image) {
                $fileName = $image->getClientOriginalName();

                $code = explode('.', $fileName);
                $ext = array_pop($code);
                $code = implode('.', $code);

                $partExists = Part::query()->where('code', $code)->first();
                if ($partExists) {
                    if ($partExists->image) {
                        if ($partExists->image->trashed()) {
                            $partExists->image->restore();
                        }
                        $partExists->image->touch();
                    } else {
                        Image::query()->create([
                            'ean' => $partExists->ean,
                            'url' => $fileName,
                            'name' => $fileName,
                        ]);
                    }
                    Storage::disk('public')->put('parts/'.$fileName, file_get_contents($image));
                }
            }

            DB::commit();
        } catch (\Throwable $e) {
            $this->rollBackThrow($e);
        }
    }

    /**
     * Delete images.
     *
     * @param ImageDto $dto
     *
     * @return void
     */
    public function destroy(ImageDto $dto): void
    {
        Image::query()
            ->whereIn('ean', $dto->imageEans)
            ->each(function ($image) {
                Storage::disk('public')->delete($image->url);
                $image->delete();
            });
    }
}
