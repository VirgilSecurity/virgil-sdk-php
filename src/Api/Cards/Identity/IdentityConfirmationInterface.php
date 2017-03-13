<?php
namespace Virgil\Sdk\Api\Cards\Identity;


use Virgil\Sdk\Client\VirgilClientInterface;

/**
 * Interface represents identity confirmation strategy.
 */
interface IdentityConfirmationInterface
{
    /**
     * Confirms the identity verification and grabs a validation token.
     *
     * @param IdentityVerificationAttemptInterface $identityVerificationAttempt
     * @param VirgilClientInterface                $virgilClient
     *
     * @return string
     */
    public function confirmIdentity(
        IdentityVerificationAttemptInterface $identityVerificationAttempt,
        VirgilClientInterface $virgilClient
    );
}
