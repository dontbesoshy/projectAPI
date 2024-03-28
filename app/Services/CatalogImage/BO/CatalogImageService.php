<?php

namespace App\Services\CatalogImage\BO;

use App\Http\Dto\CatalogImage\CatalogImageDto;
use App\Http\Dto\CatalogImage\FilesDto;
use App\Models\CatalogImage;
use App\Resources\CatalogImage\BO\CatalogImageCollection;
use App\Services\BasicService;
use Illuminate\Support\Facades\Storage;

class CatalogImageService extends BasicService
{
    /**
     * Return main photo.
     *
     * @return CatalogImageCollection
     */
    public function index(): CatalogImageCollection
    {
        $catalogImages = CatalogImage::query()->get()->sortBy('name');

        return new CatalogImageCollection($catalogImages);
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
        \DB::beginTransaction();

        try {
            foreach ($dto->catalogImages as $image) {
                $fileName = $image->getClientOriginalName();

                CatalogImage::query()->updateOrCreate([
                    'url' => 'catalog/'.$fileName,
                    'name' => $fileName,
                ]);

                Storage::disk('public')->put('catalog/'.$fileName, file_get_contents($image));
            }

            \DB::commit();
        } catch (\Throwable $e) {
            $this->rollBackThrow($e);
        }
    }

    /**
     * Delete catalog images.
     *
     * @param CatalogImageDto $dto
     *
     * @return void
     */
    public function destroy(CatalogImageDto $dto): void
    {
        CatalogImage::query()
            ->whereIn('id', $dto->catalogImageIds)
            ->each(function ($catalogImage) {
                Storage::disk('public')->delete($catalogImage->url);
                $catalogImage->delete();
            });
    }
}
