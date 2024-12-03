<?php

namespace App\Http\Dto\Promotion;

use App\Http\Dto\BasicDto;
use App\Models\Promotion\Enums\PromotionStatusEnum;
use Spatie\LaravelData\Attributes\Validation\Distinct;

class UpdatePromotionStatusDto extends BasicDto
{
    #[Distinct]
    public array $promotionIds;

    public PromotionStatusEnum $active;
}
