<?php

namespace App\Services\Order;

use App\Http\Dto\Order\OrderDto;
use App\Services\BasicService;

class OrderService extends BasicService
{
    /**
     * Send order.
     *
     * @param OrderDto $dto
     *
     * @return void
     */
    public function store(OrderDto $dto): void
    {

    }
}
