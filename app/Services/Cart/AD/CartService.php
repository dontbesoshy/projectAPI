<?php

namespace App\Services\Cart\AD;

use App\Http\Dto\Cart\CreateCartDto;
use App\Models\User\User;
use App\Resources\Cart\AD\CartCollection;
use App\Services\BasicService;
use Illuminate\Support\Facades\DB;

class CartService extends BasicService
{
    /**
     * Show cart.
     *
     * @param User $user
     *
     * @return CartCollection
     */
    public function show(User $user): CartCollection
    {
        $cartItems = $user->cart->cartItems;

        return new CartCollection($cartItems);
    }
    /**
     * Store new cart.
     *
     * @param CreateCartDto $dto
     *
     * @return void
     */
    public function store(CreateCartDto $dto): void
    {
        DB::beginTransaction();

        try {
            $user = User::find($dto->userId);

            $items = $dto->items;

            $user->cart?->delete();

            $cart = $user->cart()->create();

            foreach ($items as $item) {
                $cart->cartItems()->create([
                    'code' => $item->code,
                    'quantity' => $item->quantity,
                    'ean' => $item->ean,
                    'name' => $item->name,
                    'price' => $item->price,
                    'part_id' => $item->partId,
                ]);
            }

            DB::commit();
        } catch (\Throwable $e) {
            $this->rollBackThrow($e);
        }
    }
}