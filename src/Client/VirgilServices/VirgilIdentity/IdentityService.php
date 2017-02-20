<?php
namespace Virgil\Sdk\Client\VirgilServices\VirgilIdentity;


use Virgil\Sdk\Client\Http\HttpClientInterface;

use Virgil\Sdk\Client\Http\Requests\PostHttpRequest;

use Virgil\Sdk\Client\VirgilServices\VirgilIdentity\Model\ConfirmRequestModel;
use Virgil\Sdk\Client\VirgilServices\VirgilIdentity\Model\ValidateRequestModel;
use Virgil\Sdk\Client\VirgilServices\VirgilIdentity\Model\VerifyRequestModel;

use Virgil\Sdk\Client\VirgilServices\VirgilIdentity\Mapper\ModelMappersCollectionInterface;

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
    private $mappers;


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
        $this->mappers = $modelMappersCollection;
    }


    /**
     * @inheritdoc
     */
    public function verify(VerifyRequestModel $verifyIdentityRequestModel)
    {
        $verifyResponseModelMapper = $this->mappers->getVerifyResponseModelMapper();
        $verifyRequestModelMapper = $this->mappers->getVerifyRequestModelMapper();

        $verifyHttpRequest = new PostHttpRequest(
            $this->identityServiceParams->getVerifyUrl(), $verifyRequestModelMapper->toJson($verifyIdentityRequestModel)
        );

        $httpResponse = $this->httpClient->send($verifyHttpRequest);

        return $verifyResponseModelMapper->toModel($httpResponse->getBody());
    }


    /**
     * @inheritdoc
     */
    public function confirm(ConfirmRequestModel $confirmRequestModel)
    {
        $confirmResponseModelMapper = $this->mappers->getConfirmResponseModelMapper();
        $confirmRequestModelMapper = $this->mappers->getConfirmRequestModelMapper();

        $confirmHttpRequest = new PostHttpRequest(
            $this->identityServiceParams->getConfirmUrl(), $confirmRequestModelMapper->toJson($confirmRequestModel)
        );

        $httpResponse = $this->httpClient->send($confirmHttpRequest);

        return $confirmResponseModelMapper->toModel($httpResponse->getBody());
    }


    /**
     * @inheritdoc
     */
    public function validate(ValidateRequestModel $validateRequestModel)
    {
        $validateRequestModelMapper = $this->mappers->getValidateRequestModelMapper();

        $validateHttpRequest = new PostHttpRequest(
            $this->identityServiceParams->getValidateUrl(), $validateRequestModelMapper->toJson($validateRequestModel)
        );

        $this->httpClient->send($validateHttpRequest);

        return $this;
    }
}
