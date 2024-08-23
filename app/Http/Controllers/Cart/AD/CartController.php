<?php

namespace App\Http\Controllers\Cart\AD;

use App\Http\Controllers\Controller;
use App\Http\Dto\Cart\CreateCartDto;
use App\Services\Cart\AD\CartService;
use Illuminate\Http\JsonResponse;

class CartController extends Controller
{
    /**
     * CartController constructor.
     *
     * @param CartService $cartService
     */
    public function __construct(private readonly CartService $cartService)
    {
    }

    /**
     * Show cart.
     *
     * @return JsonResponse
     */
    public function show()
    {
        return $this->OK($this->cartService->show(auth()->user()));
    }

    /**
     * Store new cart.
     *
     * @param CreateCartDto $request
     *
     * @return JsonResponse
     */
    public function store(CreateCartDto $request): JsonResponse
    {
        $this->cartService->store($request);

        return $this->OK();
    }
}
