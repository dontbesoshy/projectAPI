<?php

namespace App\Http\Controllers\Config\BO;

use App\Http\Controllers\Controller;
use App\Http\Dto\CatalogImage\CatalogImageDto;
use App\Http\Dto\CatalogImage\FilesDto;
use App\Http\Dto\Config\CreateConfigDto;
use App\Services\CatalogImage\BO\CatalogImageService;
use App\Services\Config\BO\ConfigService;
use Illuminate\Http\JsonResponse;

class ConfigController extends Controller
{
    /**
     * ConfigController constructor.
     *
     * @param ConfigService $configService
     */
    public function __construct(private readonly ConfigService $configService)
    {
    }

    /**
     * Return configs.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->OK($this->configService->index());
    }

    /**
     * Store new config.
     *
     * @param CreateConfigDto $request
     * @return JsonResponse
     */
    public function store(CreateConfigDto $request): JsonResponse
    {
        $this->configService->store($request);
        return $this->CREATED();
    }

    /**
     * Delete image.
     *
     * @param CatalogImageDto $catalogImageIds
     *
     * @return JsonResponse
     */
    public function destroy(CatalogImageDto $catalogImageIds): JsonResponse
    {
        $this->configService->destroy($catalogImageIds);
        return $this->OK();
    }
}
