<?php

namespace App\Http\Dto\Cart;

use App\Http\Dto\BasicDto;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;

class CreateCartDto extends BasicDto
{
    public int $userId;

    #[DataCollectionOf(CartItemDto::class)]
    public DataCollection $items;
}
