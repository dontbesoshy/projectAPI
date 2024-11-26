<?php

namespace App\Services\MainPhoto\PO;

use App\Exceptions\MainPhoto\MainPhotoNotFoundException;
use App\Models\MainPhoto;
use App\Resources\MainPhoto\BO\MainPhotoResource;
use App\Services\BasicService;

class MainPhotoService extends BasicService
{
    /**
     * Return main photo.
     *
     * @return MainPhotoResource
     */
    public function index(): MainPhotoResource
    {
        $mainPhoto = MainPhoto::all()->last();

        $this->throwIf(
            !$mainPhoto,
            MainPhotoNotFoundException::class
        );

        return new MainPhotoResource($mainPhoto);
    }
}
