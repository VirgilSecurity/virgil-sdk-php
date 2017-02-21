<?php
namespace Virgil\Sdk\Client\VirgilServices\VirgilRegistrationAuthority;


/**
 * Interface provides urls for access to Registration Authority Service.
 */
interface RegistrationAuthorityServiceParamsInterface
{
    /**
     * @return string
     */
    public function getCreateUrl();


    /**
     * @param $id
     *
     * @return string
     */
    public function getDeleteUrl($id);
}
