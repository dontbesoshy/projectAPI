<?php

namespace App\Http\Dto\User\BO;

use App\Http\Dto\BasicDto;
use Spatie\LaravelData\Attributes\Validation\Distinct;

class FavoritePartsDto extends BasicDto
{
    #[Distinct]
    public ?array $partEans = [];
}
