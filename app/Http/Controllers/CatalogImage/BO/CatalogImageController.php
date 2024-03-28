<?php

namespace App\Http\Controllers\CatalogImage\BO;

use App\Http\Controllers\Controller;
use App\Http\Dto\CatalogImage\CatalogImageDto;
use App\Http\Dto\CatalogImage\FilesDto;
use App\Services\CatalogImage\BO\CatalogImageService;
use Illuminate\Http\JsonResponse;

class CatalogImageController extends Controller
{
    /**
     * ImageController constructor.
     *
     * @param CatalogImageService $catalogImageService
     */
    public function __construct(private readonly CatalogImageService $catalogImageService)
    {
    }

    /**
     * Return images.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->OK($this->catalogImageService->index());
    }

    /**
     * Store new image.
     *
     * @param FilesDto $dto
     *
     * @return JsonResponse
     */
    public function store(FilesDto $dto): JsonResponse
    {
        $this->catalogImageService->store($dto);
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
        $this->catalogImageService->destroy($catalogImageIds);
        return $this->OK();
    }
}
