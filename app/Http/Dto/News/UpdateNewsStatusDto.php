<?php

namespace App\Http\Dto\News;

use App\Http\Dto\BasicDto;
use App\Models\News\Enums\NewsStatusEnum;
use Spatie\LaravelData\Attributes\Validation\Distinct;

class UpdateNewsStatusDto extends BasicDto
{
    #[Distinct]
    public array $newsIds;

    public NewsStatusEnum $active;
}
