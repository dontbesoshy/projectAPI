<?php

namespace App\Models\News\Enums;

enum NewsStatusEnum: int
{
    case ACTIVE = 1;
    case INACTIVE = 0;
}
