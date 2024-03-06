<?php

namespace App\Services\PriceList\AD;

use App\Models\User\User;
use App\Resources\PriceList\AD\PriceListResource;
use App\Services\BasicService;

class PriceListService extends BasicService
{
    /**
     * Return all price lists.
     *
     * @param User $user
     *
     * @return PriceListResource
     */
    public function show(User $user): PriceListResource
    {
        $priceList = $user->priceLists()->first();

        return new PriceListResource($priceList);
    }
}
