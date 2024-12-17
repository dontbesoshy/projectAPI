<?php

namespace App\Resources\Order\AD;

use App\Resources\BasicResource;

class OrderResource extends BasicResource
{
    /**
     * @param $request
     *
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'comment' => $this->comment,
            'createdAt' => $this->created_at,
        ];
    }
}
