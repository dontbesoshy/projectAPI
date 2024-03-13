<?php

namespace App\Http\Dto\PriceList;

use App\Http\Dto\BasicDto;
use App\Http\Dto\Part\PartDto;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;

class UpdatePartDto extends BasicDto
{
    #[DataCollectionOf(PartDto::class)]
    public DataCollection $parts;
}
