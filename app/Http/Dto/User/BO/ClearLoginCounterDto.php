<?php

namespace App\Http\Dto\User\BO;

use App\Http\Dto\BasicDto;

class ClearLoginCounterDto extends BasicDto
{
    public ?int $userId;
}
