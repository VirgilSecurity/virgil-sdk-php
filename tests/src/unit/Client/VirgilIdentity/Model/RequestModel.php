<?php
namespace Virgil\Sdk\Tests\Unit\Client\VirgilIdentity\Model;


use Virgil\Sdk\Client\VirgilIdentity\Model\VerifyRequestModel;

class RequestModel
{
    public static function createVerifyRequestModel($identityType, $identity, array $extraFields = [])
    {
        return new VerifyRequestModel($identityType, $identity, $extraFields);
    }
}
