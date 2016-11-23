<?php

namespace Virgil\SDK\Client\Card;


use Virgil\SDK\Client\Card\Mapper\ModelMappersCollectionInterface;
use Virgil\SDK\Client\Card\Model\ErrorResponseModel;
use Virgil\SDK\Client\Card\Model\RevokeCardContentModel;
use Virgil\SDK\Client\Card\Model\SearchCriteria;
use Virgil\SDK\Client\Card\Model\SignedRequestModel;
use Virgil\SDK\Client\Http\ClientInterface;
use Virgil\SDK\Client\Http\ResponseInterface;

class CardsService implements CardsServiceInterface
{
    private $httpClient;
    private $mappers;
    private $params;
    private $defaultErrorMessages = [
        400 => 'Request error',
        401 => 'Authentication error',
        403 => 'Forbidden',
        404 => 'Entity not found',
        405 => 'Method not allowed',
        500 => 'Server error'
    ];

    /**
     * CardsService constructor.
     *
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

        $response = $this->makeRequest($request);
        return $this->mappers->getSignedResponseModelMapper()->toModel($response->getBody());
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

        $this->makeRequest($request);
    }

    public function search(SearchCriteria $model)
    {
        $request = function () use ($model) {
            return $this->httpClient->post(
                $this->params->getSearchEndpoint(), $this->mappers->getSearchCriteriaRequestMapper()->toJson($model)
            );
        };

        $response = $this->makeRequest($request);
        return $this->mappers->getSearchCriteriaResponseMapper()->toModel($response->getBody());
    }

    public function get($id)
    {
        $request = function () use ($id) {
            return $this->httpClient->get($this->params->getGetEndpoint($id));
        };

        $response = $this->makeRequest($request);
        return $this->mappers->getSignedResponseModelMapper()->toModel($response->getBody());
    }

    /**
     * Makes request to http client and gets response object.
     *
     * @param callable $request
     * @throws CardsServiceException
     * @return ResponseInterface
     */
    protected function makeRequest($request)
    {
        /** @var ResponseInterface $result */
        $result = call_user_func($request);

        if (!$result->getHttpStatus()->isSuccess()) {
            /** @var ErrorResponseModel $response */
            $response = $this->mappers->getErrorResponseModelMapper()->toModel($result->getBody());

            throw new CardsServiceException(
                $response->getMessageOrDefault(
                    $this->defaultErrorMessages[(int)$result->getHttpStatus()->getStatus()]
                ),
                $result->getHttpStatus()->getStatus()
            );
        }

        return $result;
    }
}