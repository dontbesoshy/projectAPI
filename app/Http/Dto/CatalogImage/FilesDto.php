<?php

namespace App\Http\Dto\CatalogImage;

use App\Http\Dto\BasicDto;
use Spatie\LaravelData\Attributes\DataCollectionOf;

class FilesDto extends BasicDto
{
    #[DataCollectionOf(CreateCatalogImageDto::class)]
    public array $catalogImages;
}
