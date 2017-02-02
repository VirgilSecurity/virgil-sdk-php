<?php
namespace Virgil\Sdk\Client\VirgilIdentity;


/**
 * Interface provides urls for access to Virgil Identity Service.
 */
interface IdentityServiceParamsInterface
{
    /**
     * @return string
     */
    public function getVerifyUrl();


    /**
     * @return string
     */
    public function getConfirmUrl();


    /**
     * @return string
     */
    public function getValidateUrl();
}
