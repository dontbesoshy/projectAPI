<?php

namespace App\Http\Controllers\Promotion\BO;

use App\Http\Controllers\Controller;
use App\Http\Dto\File\UploadedFileDto;
use App\Models\Promotion;
use App\Services\Promotions\BO\PromotionsService;
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
     * Return promotion.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->OK($this->promotionsService->index());
    }

    /**
     * Store new promotion.
     *
     * @param UploadedFileDto $dto
     *
     * @return JsonResponse
     */
    public function store(UploadedFileDto $dto): JsonResponse
    {
        $this->promotionsService->create($dto);
        return $this->CREATED();
    }

    /**
     * Delete promotion.
     *
     * @param Promotion $promotion
     *
     * @return JsonResponse
     */
    public function destroy(Promotion $promotion): JsonResponse
    {
        $this->promotionsService->delete($promotion);
        return $this->OK();
    }
}
