<?php

namespace App\Http\Controllers\Order\AD;

use App\Http\Controllers\Controller;
use App\Http\Dto\Order\OrderDto;
use App\Http\Dto\Order\ShowOrderDto;
use App\Models\Order\Order;
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
     * Return all orders.
     *
     * @param ShowOrderDto $request
     *
     * @return JsonResponse
     */
    public function index(ShowOrderDto $request)
    {
        return $this->OK($this->orderService->index($request));
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

    /**
     * Show order.
     *
     * @param Order $order
     * @param ShowOrderDto $request
     *
     * @return string
     */
    public function show(Order $order, ShowOrderDto $request)
    {
        return $this->orderService->show($order, $request);
    }
}
