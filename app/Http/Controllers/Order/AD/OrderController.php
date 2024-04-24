<?php

namespace App\Http\Controllers\Order\AD;

use App\Http\Controllers\Controller;
use App\Http\Dto\Order\OrderDto;
use App\Services\Order\OrderService;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    /**
     * OrderController constructor.
     *
     * @param OrderService $orderService
     */
    public function __construct(private readonly OrderService $orderService)
    {
    }

    /**
     * Send order.
     *
     * @param OrderDto $request
     *
     * @return JsonResponse
     */
    public function store(OrderDto $request): JsonResponse
    {
        $this->orderService->store($request);

        return $this->OK();
    }

    /**
     * Generate pdf.
     *
     * @param OrderDto $request
     *
     * @return JsonResponse
     */
    public function generatePdf(OrderDto $request)
    {
        return $this->orderService->generatePdf($request);
    }
}
