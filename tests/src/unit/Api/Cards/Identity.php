<?php
namespace Virgil\Sdk\Tests\Unit\Api\Cards;


use Virgil\Sdk\Api\Cards\Identity\EmailConfirmation;
use Virgil\Sdk\Api\Cards\Identity\IdentityValidationToken;
use Virgil\Sdk\Api\Cards\Identity\IdentityVerificationAttempt;

use Virgil\Sdk\Api\Cards\Identity\IdentityVerificationOptions;
use Virgil\Sdk\Client\VirgilClientInterface;

class Identity
{
    public static function createIdentityVerificationAttempt(
        VirgilClientInterface $virgilClient,
        $actionId,
        $timeToLive,
        $countToLive,
        $identityType,
        $identity
    ) {
        return new IdentityVerificationAttempt(
            $virgilClient, $actionId, $timeToLive, $countToLive, $identityType, $identity
        );
    }


    /**
     * @param VirgilClientInterface $virgilClient
     * @param                       $token
     * @param                       $identity
     * @param                       $identityType
     *
     * @return IdentityValidationToken
     */
    public static function createIdentityValidationToken(
        VirgilClientInterface $virgilClient,
        $token,
        $identity,
        $identityType
    ) {
        return new IdentityValidationToken($virgilClient, $token, $identity, $identityType);
    }


    /**
     * @param $confirmationCode
     *
     * @return EmailConfirmation
     */
    public static function createEmailConfirmation($confirmationCode)
    {
        return new EmailConfirmation($confirmationCode);
    }


    public static function createIdentityVerificationOptions(
        $extraFields,
        $countToLive,
        $timeToLive
    ) {
        return new IdentityVerificationOptions($extraFields, $countToLive, $timeToLive);
    }

}
