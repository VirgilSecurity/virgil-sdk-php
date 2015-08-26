<?php

namespace Virgil\SDK\PrivateKeys\Http\Error;

use Virgil\SDK\Common\Http\Error\Error as CommonError;

class Error extends CommonError {

    protected static $_errorMap = array(
        10001 => 'Internal application error. Route was not found.',
        10002 => 'Internal application error. Route not allowed.',

        20001 => 'Authentication error. Password validation failed',
        20002 => 'Authentication error. User data validation failed',
        20003 => 'Authentication error. Container was not found',
        20004 => 'Authentication error. Token validation failed',
        20005 => 'Authentication error. Token not found',
        20006 => 'Authentication error. Token has expired',

        30001 => 'Request Sign error. Request Sign validation failed',

        40001 => 'Container error. Container validation failed',
        40002 => 'Container error. Container was not found',
        40003 => 'Container error. Container already exists',
        40004 => 'Container error. Container password was not specified',
        40005 => 'Container error. Container password validation failed',
        40006 => 'Container error. Container was not found in PKI service',
        40007 => 'Container error. Container type validation failed',

        50001 => 'Key error. Public Key ID validation failed',
        50002 => 'Key error. Public Key ID was not found',
        50003 => 'Key error. Public Key ID already exists',
        50004 => 'Key error. Private key validation failed',
        50005 => 'Key error. Private key base64 validation failed',

        60001 => 'Verification error. Token was not found in request',
        60002 => 'Verification error. User Data validation failed',
        60003 => 'Verification error. Container was not found',
        60004 => 'Verification error. Verification token hash expired',

        70001 => 'Application Token error. Application token invalid',
        70002 => 'Application Token error. Application token service error',

        80001 => 'UUID(`request_sign_uuid`) request parameter validation failed',
        80002 => 'UUID(`request_sign_uuid`) has already used in another call. Please generate another one.'
    );
}