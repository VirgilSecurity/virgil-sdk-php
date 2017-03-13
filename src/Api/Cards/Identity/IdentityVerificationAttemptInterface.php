<?php
namespace Virgil\Sdk\Api\Cards\Identity;


/**
 * Interface represents information about identity verification process.
 */
interface IdentityVerificationAttemptInterface
{
    /**
     * Gets the operation action ID.
     *
     * @return string
     */
    public function getActionId();


    /**
     * Gets identity.
     *
     * @return string
     */
    public function getIdentity();


    /**
     * Gets identity type.
     *
     * @return string
     */
    public function getIdentityType();


    /**
     * Gets time to live.
     *
     * @return int
     */
    public function getTimeToLive();


    /**
     * Gets count to live value.
     *
     * @return int
     */
    public function getCountToLive();


    /**
     * Confirms an identity and generates a validation token that can be used to perform operations like Publish and
     * Revoke global Cards.
     *
     * @param IdentityConfirmationInterface $identityConfirmation
     *
     * @return IdentityValidationTokenInterface
     */
    public function confirm(IdentityConfirmationInterface $identityConfirmation);
}
