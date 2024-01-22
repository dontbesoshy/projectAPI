<?php

namespace App\Exceptions;

class ErrorCodes
{
    /*
    * GENERAL ERROR CODES
    */
    public const AUTHENTICATION = 'gen-0001';
    public const VALIDATION_FAILED = 'gen-0002';

    /*
    * USER ERROR CODES
    */
    public const USER_NOT_FOUND = 'usr-0001';

    /*
     * AUTH ERROR CODES
    */
    public const USERNAME_OR_PASSWORD_NOT_VALID = 'auth-0001';
}
