<?php

namespace Virgil\SDK\Client\Card;


use Virgil\SDK\Client\Card\Mapper\ModelMappersCollectionInterface;
use Virgil\SDK\Client\Card\Model\RevokeCardContentModel;
use Virgil\SDK\Client\Card\Model\SearchCriteria;
use Virgil\SDK\Client\Card\Model\SignedRequestModel;
use Virgil\SDK\Client\Http\ClientInterface;
use Virgil\SDK\Client\Http\ResponseInterface;
use Virgil\SDK\Client\JsonModelMapper;

class CardsService implements CardsServiceInterface
{
    private $httpClient;
    private $mappers;
    private $params;

    /**
     * CardsService constructor.
     * @param CardServiceParamsInterface $params
     * @param ClientInterface $httpClient
     * @param ModelMappersCollectionInterface $mappers
     */
    public function __construct(CardServiceParamsInterface $params, ClientInterface $httpClient, ModelMappersCollectionInterface $mappers)
    {
        $this->httpClient = $httpClient;
        $this->mappers = $mappers;
        $this->params = $params;
    }

    public function create(SignedRequestModel $model)
    {
        $request = function () use ($model) {
            return $this->httpClient->post(
                $this->params->getCreateEndpoint(), $this->mappers->getSignedRequestModelMapper()->toJson($model)
            );
        };

        return $this->makeRequest($request, $this->mappers->getSignedResponseModelMapper());
    }

    public function delete(SignedRequestModel $model)
    {
        $request = function () use ($model) {
            /** @var RevokeCardContentModel $cardContent */
            $cardContent = $model->getCardContent();
            return $this->httpClient->delete(
                $this->params->getDeleteEndpoint($cardContent->getId()), $this->mappers->getSignedRequestModelMapper()->toJson($model)
            );
        };

        return $this->makeRequest($request, $this->mappers->getHashMapJsonMapper());
    }

    public function search(SearchCriteria $model)
    {
        $request = function () use ($model) {
            return $this->httpClient->post(
                $this->params->getSearchEndpoint(), $this->mappers->getSearchCriteriaRequestMapper()->toJson($model)
            );
        };

        return $this->makeRequest($request, $this->mappers->getSearchCriteriaResponseMapper());
    }

    public function get($id)
    {
        $request = function () use ($id) {
            return $this->httpClient->get($this->params->getGetEndpoint($id));
        };

        return $this->makeRequest($request, $this->mappers->getSignedResponseModelMapper());
    }

    /**
     * Make request to http client and parse response to object.
     * @param callable $request
     * @param JsonModelMapper $responseMapper
     * @throws CardsServiceException
     * @return mixed
     */
    protected function makeRequest($request, JsonModelMapper $responseMapper)
    {
        /** @var ResponseInterface $result */
        $result = call_user_func($request);

        if (!$result->getHttpStatus()->isSuccess()) {
            $response = json_decode($result->getBody(), true);
            throw new CardsServiceException(
                $this->getErrorMessage($response['code']),
                $response['code']
            );
        }

        return $responseMapper->toModel($result->getBody());
    }

    /**
     * Get error message by code.
     * @param $code
     * @return string
     */
    protected function getErrorMessage($code)
    {
        return CardsErrorMessages::getMessage($code);
    }
}