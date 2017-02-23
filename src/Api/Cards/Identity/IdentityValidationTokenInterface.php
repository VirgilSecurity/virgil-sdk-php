<?php
namespace Virgil\Sdk\Api\Cards\Identity;


/**
 * Interface represents an information about identity validation token that allows to execute action that requires
 * identity authentication, like global Card creation or global Card revocation.
 */
interface IdentityValidationTokenInterface
{
    /**
     * Gets the validation token value.
     *
     * @return string
     */
    public function getValue();


    /**
     * Checks if validation token is valid and doesn't expired.
     *
     * @return bool
     */
    public function isValid();
}
