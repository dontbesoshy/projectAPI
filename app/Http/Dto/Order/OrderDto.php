<?php

namespace App\Http\Dto\Order;

use App\Http\Dto\BasicDto;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;

class OrderDto extends BasicDto
{
    #[DataCollectionOf(OrderItemDto::class)]
    public DataCollection $orderItems;
    public int $userId;

    public ?string $comment;
}
