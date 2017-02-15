<?php
namespace Virgil\Sdk\Client\VirgilServices\VirgilIdentity;


use Virgil\Sdk\Client\VirgilServices\AbstractErrorMessages;

/**
 * Class keeps list of known Identity Service errors.
 */
class IdentityErrorMessages extends AbstractErrorMessages
{
    /**
     * @inheritdoc
     */
    public function getErrorsList()
    {
        return [
            40000 => "JSON specified as a request body is invalid",
            40100 => "Identity type is invalid",
            40110 => "Identity's ttl is invalid",
            40120 => "Identity's ctl is invalid",
            40130 => "Identity's token parameter is missing",
            40140 => "Identity's token doesn't match parameters",
            40150 => "Identity's token has expired",
            40160 => "Identity's token cannot be decrypted",
            40170 => "Identity's token parameter is invalid",
            40180 => "Identity is not unconfirmed",
            40190 => "Hash to be signed parameter is invalid",
            40200 => "Email identity value validation failed",
            40210 => "Identity's confirmation code is invalid",
            40300 => "Application value is invalid",
            40310 => "Application's signed message is invalid",
            41000 => "Identity entity was not found",
            41010 => "Identity's confirmation period has expired",
        ];
    }
}
