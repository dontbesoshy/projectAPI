<?php

namespace App\Http\Dto\Image;

use App\Http\Dto\BasicDto;
use Spatie\LaravelData\Attributes\DataCollectionOf;

class FilesDto extends BasicDto
{
    #[DataCollectionOf(CreateImageDto::class)]
    public array $images;
}
