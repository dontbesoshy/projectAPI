<?php

namespace App\Http\Dto\File;

use App\Http\Dto\BasicDto;
use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Attributes\Validation\File;

class UploadedFileDto extends BasicDto
{
    #[File]
    public UploadedFile $file;
}
