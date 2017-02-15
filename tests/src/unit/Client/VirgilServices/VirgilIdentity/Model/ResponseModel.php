<?php
namespace Virgil\Sdk\Tests\Unit\Client\VirgilServices\VirgilIdentity\Model;


use Virgil\Sdk\Client\VirgilServices\VirgilIdentity\Model\ConfirmResponseModel;
use Virgil\Sdk\Client\VirgilServices\VirgilIdentity\Model\VerifyResponseModel;

class ResponseModel
{
    public static function createVerifyResponseModel($actionId)
    {
        return new VerifyResponseModel($actionId);
    }


    public static function createConfirmResponseModel($type, $value, $validationToken)
    {
        return new ConfirmResponseModel($type, $value, $validationToken);
    }
}
