<?php

namespace App\Services\Promotions\AD;

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
        $promotions = Promotion::all()->sortByDesc('id');

        return new PromotionCollection($promotions);
    }
}
