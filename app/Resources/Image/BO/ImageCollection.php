<?php

namespace App\Resources\Image\BO;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ImageCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param Request $request
     *
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'paginate' => [
                'currentPage' => $this->currentPage(),
                'totalPages' => $this->lastPage(),
                'perPage' => $this->perPage(),
                'countRecords' => $this->count(),
                'totalRecords' => $this->total(),
            ],
            'data' => $this->collection,
        ];
    }
}