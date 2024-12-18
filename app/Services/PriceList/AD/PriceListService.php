<?php

namespace App\Services\PriceList\AD;

use App\Exceptions\User\UserDoesntHavePriceListException;
use App\Models\PriceList;
use App\Models\User\User;
use App\Resources\PriceList\AD\PriceListResource;
use App\Services\BasicService;
use Illuminate\Support\Collection;

class PriceListService extends BasicService
{
    /**
     * Return all price lists.
     *
     * @param User $user
     *
     * @return ?PriceListResource
     */
    public function show(User $user): ?PriceListResource
    {
        $priceList = $user->priceLists()
            ->with(['parts' => function ($query) {
                $query->orderBy('name');
            }, 'parts.image'])
            ->first();

        if (!$priceList) {
            throw new UserDoesntHavePriceListException();
        }

        return new PriceListResource($priceList);

    }
}
