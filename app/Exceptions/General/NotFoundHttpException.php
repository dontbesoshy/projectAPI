<?php

namespace App\Exceptions\General;

use App\Exceptions\BasicException;
use App\Exceptions\ErrorCodes;
use Symfony\Component\HttpFoundation\Response;

class NotFoundHttpException extends BasicException
{
    protected function init()
    {
        $this->errorCode = ErrorCodes::NOT_FOUND_HTTP;
        $this->errorHttpCode = Response::HTTP_NOT_FOUND;
        $this->errorMsg = $this->__('exception.notFoundHttp');
    }
}
