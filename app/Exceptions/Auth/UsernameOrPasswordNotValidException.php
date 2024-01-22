<?php

namespace App\Exceptions\Auth;

use App\Exceptions\BasicException;
use App\Exceptions\ErrorCodes;
use Symfony\Component\HttpFoundation\Response;

class UsernameOrPasswordNotValidException extends BasicException
{
    protected function init()
    {
        $this->errorCode = ErrorCodes::USERNAME_OR_PASSWORD_NOT_VALID;
        $this->errorHttpCode = Response::HTTP_UNPROCESSABLE_ENTITY;
        $this->errorMsg = $this->__('exception.usernameOrPasswordNotValid');
    }
}
