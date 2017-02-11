<?php
namespace Virgil\Sdk\Tests\Unit\Client\VirgilServices\VirgilIdentity\Model;


use Virgil\Sdk\Client\VirgilServices\VirgilIdentity\Model\VerifyRequestModel;

class RequestModel
{
    public static function createVerifyRequestModel($identityType, $identity, array $extraFields = [])
    {
        return new VerifyRequestModel($identityType, $identity, $extraFields);
    }
}
