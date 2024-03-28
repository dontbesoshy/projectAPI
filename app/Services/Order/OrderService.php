<?php

namespace App\Services\Order;

use App\Http\Dto\Order\OrderDto;
use App\Models\Email;
use App\Notifications\User\BO\SendOrderNotification;
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
        Email::query()
            ->each(function (Email $email) use ($dto) {
                $email->notify(new SendOrderNotification($dto));
            });
    }
}
