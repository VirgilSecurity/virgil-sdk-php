<?php

namespace Virgil\SDK\Keys\Http\Error;

use Virgil\SDK\Common\Http\Error\Error as CommonError;

class Error extends CommonError {

    protected static $_errorMap = array(
        10000 => 'The error code returned to the user in the case of some internal error that must not be specified to client',
        10100 => 'JSON specified as a request is invalid',

        10200 => 'The request_sign_uuid parameter was already used for another request',
        10201 => 'The request_sign_uuid parameter is invalid',
        10202 => 'The request sign header not found',
        10203 => 'The Public Key header not specified or incorrect',
        10204 => 'The request sign specified is incorrect',
        10207 => 'The Public Key UUID passed in header was not confirmed yet',
        10209 => 'Public Key specified in authorization header is registered for another application',
        10210 => 'Public Key value in request body for POST /public-key endpoint must be base64 encoded value',

        10205 => 'The Virgil application token not specified or invalid',
        10206 => 'The Virgil statistics application error',

        10208 => 'Public Key value required in request body',
        20000 => 'Account object not found for id specified',
        20100 => 'Public Key object not found for id specified',
        20101 => 'Public key length invalid',
        20102 => 'Public key not specified',
        20103 => 'Public key must be base64-encoded string',
        20104 => 'Public key must contain confirmed UserData entities',
        20105 => 'Public key must contain at least one "user ID" entry',
        20107 => 'There is UDID registered for current application already',
        20108 => 'UDIDs specified are registered for several accounts',
        20110 => 'Public key is not found for any application',
        20111 => 'Public key is found for another application',
        20112 => 'Public key is registered for another application',
        20113 => 'Sign verification failed for request UUID parameter in PUT /public-key',
        20200 => 'User Data object not found for id specified',
        20202 => 'User Data type specified as user identity is invalid',
        20203 => 'Domain value specified for the domain identity is invalid',
        20204 => 'Email value specified for the email identity is invalid',
        20205 => 'Phone value specified for the phone identity is invalid',
        20210 => 'User Data integrity constraint violation',
        20211 => 'User Data confirmation entity not found',
        20212 => 'User Data confirmation token invalid',
        20213 => 'User Data was already confirmed and does not need further confirmation',
        20214 => 'User Data class specified is invalid',
        20215 => 'Domain value specified for the domain identity is invalid',
        20216 => 'This user id had been confirmed earlier',
        20217 => 'The user data is not confirmed yet',
        20218 => 'The user data value is required',
        20300 => 'User info data validation failed'

    );
}