<?php

namespace App\Http\Controllers\Promotion\AD;

use App\Http\Controllers\Controller;
use App\Services\Promotions\AD\PromotionsService;
use Illuminate\Http\JsonResponse;

class PromotionController extends Controller
{
    /**
     * PromotionController constructor.
     *
     * @param PromotionsService $promotionsService
     */
    public function __construct(private readonly PromotionsService $promotionsService)
    {
    }

    /**
     * Return main photo.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->OK($this->promotionsService->index());
    }
}
