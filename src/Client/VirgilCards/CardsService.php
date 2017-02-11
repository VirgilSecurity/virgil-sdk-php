<?php
namespace Virgil\Sdk\Client\VirgilCards;


use Virgil\Sdk\Client\VirgilCards\Mapper\ModelMappersCollectionInterface;

use Virgil\Sdk\Client\VirgilCards\Model\SearchRequestModel;
use Virgil\Sdk\Client\VirgilCards\Model\RevokeCardContentModel;
use Virgil\Sdk\Client\VirgilCards\Model\SignedRequestModel;

use Virgil\Sdk\Client\Http\HttpClientInterface;
use Virgil\Sdk\Client\Http\ResponseInterface;

use Virgil\Sdk\Client\VirgilServices\AbstractService;
use Virgil\Sdk\Client\VirgilServices\UnsuccessfulResponseException;

/**
 * TODO: move to VirgilServices namespaces on major version.
 *
 * Class responsible for retrieving, revocation or creation Virgil cards.
 */
class CardsService extends AbstractService implements CardsServiceInterface
{
    /** @var HttpClientInterface $httpClient */
    protected $httpClient;

    /** @var ModelMappersCollectionInterface $mappers */
    protected $mappers;

    /** @var CardsServiceParamsInterface $params */
    protected $params;


    /**
     * Class constructor.
     *
     * @param CardsServiceParamsInterface     $params
     * @param HttpClientInterface             $httpClient
     * @param ModelMappersCollectionInterface $mappers
     */
    public function __construct(
        CardsServiceParamsInterface $params,
        HttpClientInterface $httpClient,
        ModelMappersCollectionInterface $mappers
    ) {
        $this->httpClient = $httpClient;
        $this->mappers = $mappers;
        $this->params = $params;
    }


    /**
     * @inheritdoc
     */
    public function create(SignedRequestModel $model)
    {
        $signedResponseModelMapper = $this->mappers->getSignedResponseModelMapper();

        $request = function () use ($model) {
            $signedRequestModelMapper = $this->mappers->getSignedRequestModelMapper();

            return $this->httpClient->post(
                $this->params->getCreateUrl(),
                $signedRequestModelMapper->toJson($model)
            );
        };

        $response = $this->makeRequest($request);

        return $signedResponseModelMapper->toModel($response->getBody());
    }


    /**
     * @inheritdoc
     */
    public function delete(SignedRequestModel $model)
    {
        $request = function () use ($model) {
            $signedRequestModelMapper = $this->mappers->getSignedRequestModelMapper();

            /** @var RevokeCardContentModel $cardContent */
            $cardContent = $model->getRequestContent();

            return $this->httpClient->delete(
                $this->params->getDeleteUrl($cardContent->getId()),
                $signedRequestModelMapper->toJson($model)
            );
        };

        $this->makeRequest($request);

        return $this;
    }


    /**
     * @inheritdoc
     */
    public function search(SearchRequestModel $model)
    {
        $signedResponseModelsMapper = $this->mappers->getSignedResponseModelsMapper();

        $request = function () use ($model) {
            $searchRequestModelMapper = $this->mappers->getSearchRequestModelMapper();

            return $this->httpClient->post(
                $this->params->getSearchUrl(),
                $searchRequestModelMapper->toJson($model)
            );
        };

        $response = $this->makeRequest($request);

        return $signedResponseModelsMapper->toModel($response->getBody());
    }


    /**
     * @inheritdoc
     */
    public function get($id)
    {
        $signedResponseModelMapper = $this->mappers->getSignedResponseModelMapper();

        $request = function () use ($id) {
            return $this->httpClient->get($this->params->getGetUrl($id));
        };

        $response = $this->makeRequest($request);

        return $signedResponseModelMapper->toModel($response->getBody());
    }


    /**
     * @param callable $request
     *
     * @return ResponseInterface
     *
     * @throws CardsServiceException
     */
    protected function makeRequest($request)
    {
        try {
            return parent::makeRequest($request);
        } catch (UnsuccessfulResponseException $exception) {
            throw new CardsServiceException(
                $exception->getMessage(), $exception->getHttpStatusCode(), $exception->getServiceErrorCode()
            );
        }
    }
}
