<?php
namespace Virgil\Sdk\Api\Cards;


use Virgil\Sdk\Contracts\PublicKeyInterface;

use Virgil\Sdk\Api\Cards\Identity\IdentityVerificationAttemptInterface;
use Virgil\Sdk\Api\Cards\Identity\IdentityVerificationOptionsInterface;

/**
 * Interface represents an information about the user and his public key. The Virgil Card identifies the user by one of
 * his available types, such as an email, a phone number, etc.
 */
interface VirgilCardInterface
{
    /**
     * Returns public key.
     *
     * @return PublicKeyInterface
     */
    public function getPublicKey();


    /**
     * Initiates an identity verification process for current Card identity type. It is only working for Global
     * identity types like Email.
     *
     * @param IdentityVerificationOptionsInterface $identityVerificationOptions
     *
     * @return IdentityVerificationAttemptInterface
     */
    public function checkIdentity(IdentityVerificationOptionsInterface $identityVerificationOptions = null);
}
