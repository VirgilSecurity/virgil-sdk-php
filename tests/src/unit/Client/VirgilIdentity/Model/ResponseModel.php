<?php
namespace Virgil\Sdk\Tests\Unit\Client\VirgilIdentity\Model;


use Virgil\Sdk\Client\VirgilIdentity\Model\VerifyResponseModel;

class ResponseModel
{
    public static function createVerifyResponseModel($actionId)
    {
        return new VerifyResponseModel($actionId);
    }
}
