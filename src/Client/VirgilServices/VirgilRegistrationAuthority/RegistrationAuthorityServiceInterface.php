<?php
namespace Virgil\Sdk\Client\VirgilServices\VirgilRegistrationAuthority;


use Virgil\Sdk\Client\VirgilServices\Model\SignedRequestModel;
use Virgil\Sdk\Client\VirgilServices\Model\SignedResponseModel;

/**
 * Interface provides methods for interaction with Virgil Registration Authority Service.
 */
interface RegistrationAuthorityServiceInterface
{
    /**
     * Creates the Virgil Card entity (global or application).
     *
     * @param SignedRequestModel $createRequestModel
     *
     * @return SignedResponseModel
     */
    public function create(SignedRequestModel $createRequestModel);


    /**
     * Removes the Virgil Card entity (global or application).
     *
     * @param SignedRequestModel $revokeRequestModel
     *
     * @return $this
     */
    public function delete(SignedRequestModel $revokeRequestModel);
}
