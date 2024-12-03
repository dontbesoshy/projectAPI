<?php

namespace App\Services\Promotions\AD;

use App\Models\Promotion\Enums\PromotionStatusEnum;
use App\Models\Promotion\Promotion;
use App\Resources\Promotion\AD\PromotionCollection;
use App\Services\BasicService;

class PromotionsService extends BasicService
{
    /**
     * Return main photo.
     *
     * @return PromotionCollection
     */
    public function index(): PromotionCollection
    {
        $promotions = Promotion::query()->where('active', PromotionStatusEnum::ACTIVE)->get()->sortByDesc('id');

        return new PromotionCollection($promotions);
    }
}
