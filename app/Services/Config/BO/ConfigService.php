<?php

namespace App\Services\Config\BO;

use App\Http\Dto\CatalogImage\CatalogImageDto;
use App\Http\Dto\CatalogImage\FilesDto;
use App\Http\Dto\Config\CreateConfigDto;
use App\Models\CatalogImage;
use App\Models\Config\Config;
use App\Resources\Config\ConfigCollection;
use App\Services\BasicService;
use Illuminate\Support\Facades\Storage;

class ConfigService extends BasicService
{
    /**
     * Return configs.
     *
     * @return ConfigCollection
     */
    public function index(): ConfigCollection
    {
        $configs = Config::query()->get();

        return new ConfigCollection($configs);
    }

    /**
     * Store a new image.
     *
     * @param CreateConfigDto $dto
     *
     * @return void
     */
    public function store(CreateConfigDto $dto): void
    {
        $config = Config::query()->where('type', $dto->type)->first();
        if ($config) {
            $config->update([
                'value' => $dto->value,
                'active' => $dto->active,
            ]);
        } else {
            Config::query()->create([
                'type' => $dto->type,
                'value' => $dto->value,
                'active' => $dto->active,
            ]);
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
