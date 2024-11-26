<?php

namespace App\Exceptions\User;

use App\Exceptions\BasicException;
use App\Exceptions\ErrorCodes;
use Symfony\Component\HttpFoundation\Response;

class UserAlreadyExistsInThisEmailException extends BasicException
{
    protected function init()
    {
        $this->errorCode = ErrorCodes::USER_ALREADY_EXISTS_IN_THIS_EMAIL;
        $this->errorHttpCode = Response::HTTP_UNPROCESSABLE_ENTITY;
        $this->errorMsg = $this->__('exception.userAlreadyExistsInThisEmail');
    }
}
