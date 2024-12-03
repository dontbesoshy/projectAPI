<?php

namespace App\Models\Promotion\Enums;

enum PromotionStatusEnum: int
{
    case ACTIVE = 1;
    case INACTIVE = 0;
}
