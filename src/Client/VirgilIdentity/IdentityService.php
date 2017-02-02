<?php
namespace Virgil\Sdk\Client\VirgilIdentity;


use Virgil\Sdk\Client\Http\HttpClientInterface;

use Virgil\Sdk\Client\VirgilIdentity\Mapper\ModelMappersCollectionInterface;

use Virgil\Sdk\Client\VirgilIdentity\Model\ConfirmRequestModel;
use Virgil\Sdk\Client\VirgilIdentity\Model\ConfirmResponseModel;
use Virgil\Sdk\Client\VirgilIdentity\Model\ValidateRequestModel;
use Virgil\Sdk\Client\VirgilIdentity\Model\VerifyRequestModel;
use Virgil\Sdk\Client\VirgilIdentity\Model\VerifyResponseModel;

/**
 * Virgil Identity service is responsible for validation of user's identities like email, application, etc.
 */
class IdentityService implements IdentityServiceInterface
{
    /** @var IdentityServiceParamsInterface */
    private $identityServiceParams;

    /** @var HttpClientInterface */
    private $httpClient;

    /** @var ModelMappersCollectionInterface */
    private $modelMappersCollection;


    /**
     * Class constructor.
     *
     * @param IdentityServiceParamsInterface  $identityServiceParams
     * @param HttpClientInterface             $httpClient
     * @param ModelMappersCollectionInterface $modelMappersCollection
     */
    public function __construct(
        IdentityServiceParamsInterface $identityServiceParams,
        HttpClientInterface $httpClient,
        ModelMappersCollectionInterface $modelMappersCollection
    ) {
        $this->identityServiceParams = $identityServiceParams;
        $this->httpClient = $httpClient;
        $this->modelMappersCollection = $modelMappersCollection;
    }


    /**
     * @inheritdoc
     */
    public function verify(VerifyRequestModel $verifyIdentityRequest)
    {
        // TODO: Implement verify() method.
    }


    /**
     * @inheritdoc
     */
    public function confirm(ConfirmRequestModel $confirmRequestModel)
    {
        // TODO: Implement confirm() method.
    }


    /**
     * @inheritdoc
     */
    public function validate(ValidateRequestModel $validateRequestModel)
    {
        // TODO: Implement validate() method.
    }
}
