<?php
namespace Virgil\Sdk\Api\Cards\Identity;


/**
 * Interface represents additional options for identity verification.
 */
interface IdentityVerificationOptionsInterface
{
    /**
     * Gets a key/value dictionary that represents a user fields. In some cases it could be necessary to pass
     * some parameters to verification server and receive them back in an email. If type of an identity is email, all
     * values passed to extra fields will be passed back in an email in a hidden form with extra hidden fields.
     *
     * @return array
     */
    public function getExtraFields();


    /**
     * Gets the "time to live" value is used to limit the lifetime of the token in seconds.
     *
     * @return int
     */
    public function getTimeToLive();


    /**
     * Gets the "count to live" parameter is used to restrict the number of validation token usages.
     *
     * @return int
     */
    public function getCountToLive();
}
