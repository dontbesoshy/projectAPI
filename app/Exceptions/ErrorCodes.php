<?php

namespace App\Exceptions;

class ErrorCodes
{
    /*
    * GENERAL ERROR CODES
    */
    public const AUTHENTICATION = 'gen-0001';
    public const VALIDATION_FAILED = 'gen-0002';
    public const NOT_FOUND_HTTP = 'gen-0003';
    public const MODEL_NOT_FOUND = 'gen-0004';

    /*
    * USER ERROR CODES
    */
    public const USER_NOT_FOUND = 'usr-0001';
    public const REGISTER_TOKEN_NOT_VALID = 'usr-0002';
    public const USER_NOT_VERIFIED = 'usr-0003';

    public const USER_DOESNT_HAVE_PRICE_LIST = 'usr-0004';
    public const USER_ALREADY_EXISTS_IN_THIS_EMAIL = 'usr-0006';

    /*
     * AUTH ERROR CODES
    */
    public const USERNAME_OR_PASSWORD_NOT_VALID = 'auth-0001';

    /*
    * MAIN PHOTO ERROR CODES
    */
    public const MAIN_PHOTO_NOT_FOUND = 'mainPhoto-0001';
}
