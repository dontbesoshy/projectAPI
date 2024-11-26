<?php

namespace App\Http\Controllers\News\AD;

use App\Http\Controllers\Controller;
use App\Services\News\AD\NewsService;
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
}
