<?php

namespace App\Services\CatalogImage\BO;

use App\Http\Dto\CatalogImage\CreateCatalogImageDto;
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
        $queryBuilder = CatalogImage::query()->latest();

        return new CatalogImageCollection($queryBuilder->customPaginate(config('settings.pagination.perPage')));
    }

    /**
     * Store a new image.
     *
     * @param CreateCatalogImageDto $dto
     *
     * @return void
     */
    public function store(CreateCatalogImageDto $dto): void
    {
        \DB::beginTransaction();

        try {
            $fileName = $dto->file->getClientOriginalName();

            Storage::disk('public')->put($fileName, file_get_contents($dto->file));

            CatalogImage::updateOrCreate([
                'url' => $fileName,
                'name' => $fileName,
            ]);

            \DB::commit();
        } catch (\Throwable $e) {
            $this->rollBackThrow($e);
        }
    }
}
