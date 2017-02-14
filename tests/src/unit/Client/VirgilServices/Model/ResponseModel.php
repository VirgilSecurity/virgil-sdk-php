<?php
namespace Virgil\Sdk\Tests\Unit\Client\VirgilServices\Model;


use Virgil\Sdk\Client\VirgilServices\Model\ErrorResponseModel;

class ResponseModel
{
    public static function createErrorResponseModel($code, $message)
    {
        return new ErrorResponseModel($code, $message);
    }
}
