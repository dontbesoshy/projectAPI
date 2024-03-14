<?php

namespace App\Http\Dto\Image;

use App\Http\Dto\BasicDto;
use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Attributes\Validation\File;

class CreateImageDto extends BasicDto
{
    #[File]
    public UploadedFile $file;

    public int $partId;
}
