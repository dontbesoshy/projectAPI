<?php

namespace App\Exceptions\User;

use App\Exceptions\BasicException;
use App\Exceptions\ErrorCodes;
use Symfony\Component\HttpFoundation\Response;

class UserDoesntHavePriceListException extends BasicException
{
    protected function init()
    {
        $this->errorCode = ErrorCodes::USER_DOESNT_HAVE_PRICE_LIST;
        $this->errorHttpCode = Response::HTTP_UNPROCESSABLE_ENTITY;
        $this->errorMsg = $this->__('exception.userDoesntHavePriceList');
    }
}
