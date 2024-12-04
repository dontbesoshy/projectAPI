<?php

namespace App\Http\Dto\Config;

use App\Http\Dto\BasicDto;
use App\Models\Config\Enums\ConfigTypeEnum;

class CreateConfigDto extends BasicDto
{
    public ConfigTypeEnum $type;

    public ?string $value;

    public bool $active;
}
