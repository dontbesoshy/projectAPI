<?php

namespace App\Exceptions\MainPhoto;

use App\Exceptions\BasicException;
use App\Exceptions\ErrorCodes;
use Symfony\Component\HttpFoundation\Response;

class MainPhotoNotFoundException extends BasicException
{
    protected function init()
    {
        $this->errorCode = ErrorCodes::MAIN_PHOTO_NOT_FOUND;
        $this->errorHttpCode = Response::HTTP_UNPROCESSABLE_ENTITY;
        $this->errorMsg = $this->__('exception.mainPhotoNotFound');
    }
}
