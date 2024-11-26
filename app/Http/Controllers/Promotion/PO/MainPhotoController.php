<?php

namespace App\Http\Controllers\MainPhoto\PO;

use App\Http\Controllers\Controller;
use App\Services\MainPhoto\PO\MainPhotoService;
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
}
