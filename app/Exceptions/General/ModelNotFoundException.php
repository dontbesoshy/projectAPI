<?php

namespace App\Exceptions\General;

use App\Exceptions\BasicException;
use App\Exceptions\ErrorCodes;
use App\Exceptions\InternalErrorCodes;
use Symfony\Component\HttpFoundation\Response;

class ModelNotFoundException extends BasicException
{
    protected function init()
    {
        $this->errorCode = ErrorCodes::MODEL_NOT_FOUND;
        $this->errorHttpCode = Response::HTTP_NOT_FOUND;
        $this->errorMsg = $this->__('exception.modelNotFound');

        $this->loggerSettings['backtrace'] = false;
    }
}
