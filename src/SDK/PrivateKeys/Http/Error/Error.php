<?php

namespace Virgil\SDK\PrivateKeys\Http\Error;

use Virgil\SDK\Common\Http\Error\Error as CommonError;

class Error extends CommonError {

    protected static $_errorMap = array(
        20001 => 'Authentication password validation failed',
        20002 => 'Authentication user data validation failed',
        20003 => 'Authentication account was not found by provided user data',
        20004 => 'Authentication token validation failed',
        20005 => 'Authentication token not found',
        20006 => 'Authentication token has expired',
        30001 => 'Signed validation failed',
        40001 => 'Account validation failed',
        40002 => 'Account was not found',
        40003 => 'Account already exists',
        40004 => 'Account password was not specified',
        40005 => 'Account password validation failed',
        40006 => 'Account was not found in PKI service',
        40007 => 'Account type validation failed',
        50001 => 'Public Key validation failed',
        50002 => 'Public Key was not found',
        50003 => 'Public Key already exists',
        50004 => 'Public Key private key validation failed',
        50005 => 'Public Key private key base64 validation failed',
        60001 => 'Token was not found in request',
        60002 => 'User Data validation failed',
        60003 => 'Account was not found by user data',
        60004 => 'Verification token has expired'
    );
}