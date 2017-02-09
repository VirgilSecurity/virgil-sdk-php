<?php
namespace Virgil\Sdk\Client\VirgilIdentity;


use Virgil\Sdk\Client\Http\HttpClientInterface;

use Virgil\Sdk\Client\VirgilServices\AbstractService;

use Virgil\Sdk\Client\VirgilIdentity\Mapper\ModelMappersCollectionInterface;

use Virgil\Sdk\Client\VirgilIdentity\Model\ConfirmRequestModel;
use Virgil\Sdk\Client\VirgilIdentity\Model\ConfirmResponseModel;
use Virgil\Sdk\Client\VirgilIdentity\Model\ValidateRequestModel;
use Virgil\Sdk\Client\VirgilIdentity\Model\VerifyRequestModel;
use Virgil\Sdk\Client\VirgilIdentity\Model\VerifyResponseModel;

/**
 * TODO: move to VirgilServices namespaces on major version.
 *
 * Virgil Identity service is responsible for validation of user's identities like email, application, etc.
 */
class IdentityService extends AbstractService implements IdentityServiceInterface
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

        $request = function () use ($verifyIdentityRequestModel) {
            $verifyRequestModelMapper = $this->mappers->getVerifyRequestModelMapper();

            return $this->httpClient->post(
                $this->identityServiceParams->getVerifyUrl(),
                $verifyRequestModelMapper->toJson($verifyIdentityRequestModel)
            );
        };

        $response = $this->makeRequest($request);

        return $verifyResponseModelMapper->toModel($response->getBody());
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
