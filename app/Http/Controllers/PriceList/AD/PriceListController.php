<?php

namespace App\Http\Controllers\PriceList\AD;

use App\Http\Controllers\Controller;
use App\Services\PriceList\AD\PriceListService;
use Illuminate\Http\JsonResponse;

class PriceListController extends Controller
{
    /**
     * PriceListController constructor.
     *
     * @param PriceListService $priceListService
     */
    public function __construct(private readonly PriceListService $priceListService)
    {
    }

    /**
     * Return all price lists.
     *
     * @return JsonResponse
     */
    public function show(): JsonResponse
    {
        return $this->OK($this->priceListService->show(auth()->user()));
    }
}
