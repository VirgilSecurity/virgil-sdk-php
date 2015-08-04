<?php

namespace Virgil\SDK\Common\Http\Error;

use Virgil\SDK\Common\Http\ResponseInterface;

class Error {

    protected static $_errorMap = array(

    );

    protected static $_httpErrorMap = array(
        ResponseInterface::HTTP_CODE_BAD_REQUEST  => 'Request error',
        ResponseInterface::HTTP_CODE_UNAUTHORIZED => 'Authorization error',
        ResponseInterface::HTTP_CODE_METHOD_NOT_ALLOWED => 'Method not allowed',
        ResponseInterface::HTTP_CODE_NOT_FOUND => 'Entity not found',
        ResponseInterface::HTTP_CODE_INTERNAL_SERVER_ERROR => 'Internal Server Error'
    );

    public static function getErrorMessage($errorCode, $default = null) {
        if(isset(static::$_errorMap[$errorCode])) {
            return static::$_errorMap[$errorCode];
        }

        return $default;
    }

    public static function getHttpErrorMessage($httpStatusCode, $errorCode = null, $default = null) {
        if(static::getErrorMessage($errorCode)) {
            return static::getErrorMessage($errorCode);
        }

        if(isset(static::$_httpErrorMap[$httpStatusCode])) {
            return static::$_httpErrorMap[$httpStatusCode];
        }

        return $default;
    }

}