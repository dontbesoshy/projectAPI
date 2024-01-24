<?php

namespace App\Exceptions\User;

use App\Exceptions\BasicException;
use App\Exceptions\ErrorCodes;
use Symfony\Component\HttpFoundation\Response;

class UserNotVerifiedException extends BasicException
{
    protected function init()
    {
        $this->errorCode = ErrorCodes::USER_NOT_VERIFIED;
        $this->errorHttpCode = Response::HTTP_UNPROCESSABLE_ENTITY;
        $this->errorMsg = $this->__('exception.userNotVerified');
    }
}
