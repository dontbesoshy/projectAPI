<?php

namespace App\Exceptions\User\RegisterToken;

use App\Exceptions\BasicException;
use App\Exceptions\ErrorCodes;
use Symfony\Component\HttpFoundation\Response;

class RegisterTokenNotValidException extends BasicException
{
    protected function init()
    {
        $this->errorCode = ErrorCodes::REGISTER_TOKEN_NOT_VALID;
        $this->errorHttpCode = Response::HTTP_UNPROCESSABLE_ENTITY;
        $this->errorMsg = $this->__('exception.registerTokenNotValid');
    }
}
