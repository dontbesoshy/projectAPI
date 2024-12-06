<?php

namespace App\Services\PriceList\AD;

use App\Exceptions\User\UserDoesntHavePriceListException;
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
     * @return ?PriceListResource
     */
    public function show(User $user): ?PriceListResource
    {
        $priceList = $user->priceLists()->with('parts.image')->first();

        if (!$priceList) {
            return null;
        }

        $uniqueParts = $priceList->parts;
        $priceList->setRelation('parts', $uniqueParts);

        $this->throwIf(
            !$priceList,
            UserDoesntHavePriceListException::class
        );

        return new PriceListResource($priceList);
    }
}
