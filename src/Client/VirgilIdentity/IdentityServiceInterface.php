<?php
namespace Virgil\Sdk\Client\VirgilIdentity;


use Virgil\Sdk\Client\VirgilIdentity\Model\ConfirmRequestModel;
use Virgil\Sdk\Client\VirgilIdentity\Model\ConfirmResponseModel;
use Virgil\Sdk\Client\VirgilIdentity\Model\ValidateRequestModel;
use Virgil\Sdk\Client\VirgilIdentity\Model\VerifyRequestModel;
use Virgil\Sdk\Client\VirgilIdentity\Model\VerifyResponseModel;

/**
 * Interface provides methods for interaction with Virgil Identity Service.
 */
interface IdentityServiceInterface
{
    /**
     * Initiates a process to verify an Identity.
     *
     * @param VerifyRequestModel $verifyIdentityRequestModel
     *
     * @return VerifyResponseModel
     */
    public function verify(VerifyRequestModel $verifyIdentityRequestModel);


    /**
     * Confirms the identity from the verify step to obtain an identity confirmation token.
     *
     * @param ConfirmRequestModel $confirmRequestModel
     *
     * @return ConfirmResponseModel
     */
    public function confirm(ConfirmRequestModel $confirmRequestModel);


    /**
     * Validates the passed token from confirm step.
     *
     * @param ValidateRequestModel $validateRequestModel
     *
     * @return $this
     */
    public function validate(ValidateRequestModel $validateRequestModel);
}
