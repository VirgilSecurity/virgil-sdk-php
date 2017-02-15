<?php
namespace Virgil\Sdk\Client\VirgilServices\VirgilRegistrationAuthority;


use Virgil\Sdk\Client\VirgilServices\VirgilRegistrationAuthority\Model\CreateRequestModel;
use Virgil\Sdk\Client\VirgilServices\VirgilRegistrationAuthority\Model\CreateResponseModel;
use Virgil\Sdk\Client\VirgilServices\VirgilRegistrationAuthority\Model\RevokeRequestModel;


/**
 * Interface provides methods for interaction with Virgil Registration Authority Service.
 */
interface RegistrationAuthorityInterface
{
    /**
     * Creates the Virgil Card entity (global or application).
     *
     * @param CreateRequestModel $createRequestModel
     *
     * @return CreateResponseModel
     */
    public function create(CreateRequestModel $createRequestModel);


    /**
     * Removes the Virgil Card entity (global or application).
     *
     * @param RevokeRequestModel $revokeRequestModel
     *
     * @return $this
     */
    public function delete(RevokeRequestModel $revokeRequestModel);
}
