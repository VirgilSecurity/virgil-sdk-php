<?php
namespace Virgil\Sdk\Client\VirgilServices\VirgilRegistrationAuthority;


use Virgil\Sdk\Client\Http\HttpClientInterface;

use Virgil\Sdk\Client\Http\Requests\DeleteHttpRequest;
use Virgil\Sdk\Client\Http\Requests\PostHttpRequest;

use Virgil\Sdk\Client\VirgilServices\Model\RevokeCardContentModel;
use Virgil\Sdk\Client\VirgilServices\Model\SignedRequestModel;

use Virgil\Sdk\Client\VirgilServices\VirgilRegistrationAuthority\Mapper\ModelMappersCollectionInterface;

/**
 * The Virgil Registration Authority (VRA) service is a dedicated service to authorize the creation of either global
 * Virgil Cards or Virgil Cards with an application scope that is confirmed by a 3rd-party (the VRA for now).
 */
class RegistrationAuthorityService implements RegistrationAuthorityServiceInterface
{
    /** @var RegistrationAuthorityServiceParamsInterface */
    private $registrationAuthorityParams;

    /** @var HttpClientInterface */
    private $httpClient;

    /** @var ModelMappersCollectionInterface */
    private $mappers;


    /**
     * Class constructor.
     *
     * @param RegistrationAuthorityServiceParamsInterface $registrationAuthorityParams
     * @param HttpClientInterface                         $httpClient
     * @param ModelMappersCollectionInterface             $mappers
     */
    public function __construct(
        RegistrationAuthorityServiceParamsInterface $registrationAuthorityParams,
        HttpClientInterface $httpClient,
        ModelMappersCollectionInterface $mappers
    ) {
        $this->registrationAuthorityParams = $registrationAuthorityParams;
        $this->httpClient = $httpClient;
        $this->mappers = $mappers;
    }


    /**
     * @inheritdoc
     */
    public function create(SignedRequestModel $createRequestModel)
    {
        $signedResponseModelMapper = $this->mappers->getSignedResponseModelMapper();
        $signedRequestModelMapper = $this->mappers->getSignedRequestModelMapper();

        $createCardHttpRequest = new PostHttpRequest(
            $this->registrationAuthorityParams->getCreateUrl(), $signedRequestModelMapper->toJson($createRequestModel)
        );

        $httpResponse = $this->httpClient->send($createCardHttpRequest);

        return $signedResponseModelMapper->toModel($httpResponse->getBody());
    }


    /**
     * @inheritdoc
     */
    public function delete(SignedRequestModel $revokeRequestModel)
    {
        $signedRequestModelMapper = $this->mappers->getSignedRequestModelMapper();

        /** @var RevokeCardContentModel $cardContent */
        $cardContent = $revokeRequestModel->getRequestContent();

        $createCardHttpRequest = new DeleteHttpRequest(
            $this->registrationAuthorityParams->getDeleteUrl($cardContent->getId()),
            $signedRequestModelMapper->toJson($revokeRequestModel)
        );

        $this->httpClient->send($createCardHttpRequest);

        return $this;
    }
}
