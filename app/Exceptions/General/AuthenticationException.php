<?php

namespace App\Exceptions\General;

use App\Exceptions\BasicException;
use App\Exceptions\ErrorCodes;
use Symfony\Component\HttpFoundation\Response;

class AuthenticationException extends BasicException
{
    protected function init()
    {
        $this->errorCode = ErrorCodes::AUTHENTICATION;
        $this->errorHttpCode = Response::HTTP_UNAUTHORIZED;
        $this->errorMsg = $this->__('exception.authentication');

        $this->loggerSettings['backtrace'] = false;
    }
}
