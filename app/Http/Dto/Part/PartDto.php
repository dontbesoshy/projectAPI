<?php

namespace App\Http\Dto\Part;

use App\Http\Dto\BasicDto;

class PartDto extends BasicDto
{
    public ?int $id;
    public ?string $ean;
    public ?string $name;
    public ?string $code;
    public ?string $price;
}
