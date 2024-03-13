<?php

namespace App\Http\Controllers\MainPhoto\BO;

use App\Http\Controllers\Controller;
use App\Http\Dto\File\UploadedFileDto;
use App\Services\MainPhoto\BO\MainPhotoService;
use Illuminate\Http\JsonResponse;

class MainPhotoController extends Controller
{
    /**
     * MainPhotoController constructor.
     *
     * @param MainPhotoService $mainPhotoService
     */
    public function __construct(private readonly MainPhotoService $mainPhotoService)
    {
    }

    /**
     * Return main photo.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->OK($this->mainPhotoService->index());
    }

    /**
     * Store new main photo.
     *
     * @param UploadedFileDto $dto
     *
     * @return JsonResponse
     */
    public function store(UploadedFileDto $dto): JsonResponse
    {
        $this->mainPhotoService->create($dto);
        return $this->CREATED();
    }
}
