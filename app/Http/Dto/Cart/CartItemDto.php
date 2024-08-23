<?php

namespace App\Http\Dto\Cart;

use App\Http\Dto\BasicDto;

class CartItemDto extends BasicDto
{
    public string $code;
    public string $ean;
    public string $partId;
    public string $name;
    public string $price;
    public int $quantity;
}
