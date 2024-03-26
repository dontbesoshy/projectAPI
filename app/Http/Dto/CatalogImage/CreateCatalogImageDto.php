<?php

namespace App\Http\Dto\CatalogImage;

use App\Http\Dto\BasicDto;
use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Attributes\Validation\File;

class CreateCatalogImageDto extends BasicDto
{
    #[File]
    public UploadedFile $file;
}
