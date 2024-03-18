<?php

namespace App\Http\Dto\Order;

use App\Http\Dto\BasicDto;

class OrderItemDto extends BasicDto
{
    public string $ean;
    public string $name;
    public string $code;
    public string $price;
    public string $pieces;

}
