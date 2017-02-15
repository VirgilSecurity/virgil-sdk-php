<?php
namespace Virgil\Sdk\Tests\Unit\Client\VirgilServices\VirgilIdentity\Model;


use Virgil\Sdk\Client\VirgilServices\VirgilIdentity\Model\ConfirmRequestModel;
use Virgil\Sdk\Client\VirgilServices\VirgilIdentity\Model\TokenModel;
use Virgil\Sdk\Client\VirgilServices\VirgilIdentity\Model\ValidateRequestModel;
use Virgil\Sdk\Client\VirgilServices\VirgilIdentity\Model\VerifyRequestModel;

class RequestModel
{
    public static function createVerifyRequestModel($identityType, $identity, array $extraFields = [])
    {
        return new VerifyRequestModel($identityType, $identity, $extraFields);
    }


    public static function createConfirmRequestModel($confirmationCode, $actionId, array $token = null)
    {
        return new ConfirmRequestModel($confirmationCode, $actionId, new TokenModel(...$token));
    }


    public static function createValidateRequestModel($type, $value, $validationToken)
    {
        return new ValidateRequestModel($type, $value, $validationToken);
    }
}
