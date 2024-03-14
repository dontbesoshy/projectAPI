<?php

namespace App\Http\Controllers\Image\BO;

use App\Http\Controllers\Controller;
use App\Http\Dto\File\UploadedFileDto;
use App\Http\Dto\Image\CreateImageDto;
use App\Services\Image\BO\ImageService;
use Illuminate\Http\JsonResponse;

class ImageController extends Controller
{
    /**
     * ImageController constructor.
     *
     * @param ImageService $imageService
     */
    public function __construct(private readonly ImageService $imageService)
    {
    }

    /**
     * Return images.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->OK($this->imageService->index());
    }

    /**
     * Store new image.
     *
     * @param CreateImageDto $dto
     *
     * @return JsonResponse
     */
    public function store(CreateImageDto $dto): JsonResponse
    {
        $this->imageService->store($dto);
        return $this->CREATED();
    }
}
