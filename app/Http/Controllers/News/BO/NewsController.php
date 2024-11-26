<?php

namespace App\Http\Controllers\News\BO;

use App\Http\Controllers\Controller;
use App\Http\Dto\File\UploadedFileDto;
use App\Models\News;
use App\Services\News\BO\NewsService;
use Illuminate\Http\JsonResponse;

class NewsController extends Controller
{
    /**
     * NewsController constructor.
     *
     * @param NewsService $newsService
     */
    public function __construct(private readonly NewsService $newsService)
    {
    }

    /**
     * Return news.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->OK($this->newsService->index());
    }

    /**
     * Store new news.
     *
     * @param UploadedFileDto $dto
     *
     * @return JsonResponse
     */
    public function store(UploadedFileDto $dto): JsonResponse
    {
        $this->newsService->create($dto);
        return $this->CREATED();
    }

    /**
     * Delete news.
     *
     * @param News $news
     *
     * @return JsonResponse
     */
    public function destroy(News $news): JsonResponse
    {
        $this->newsService->delete($news);
        return $this->OK();
    }
}
