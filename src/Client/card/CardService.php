<?php

namespace Virgil\SDK\Client\Card;


use Virgil\SDK\Client\Card\Mapper\ModelMappersCollectionInterface;
use Virgil\SDK\Client\Card\Model\SearchCriteria;
use Virgil\SDK\Client\Card\Model\SignedRequestModel;
use Virgil\SDK\Client\HttpClient;

class CardService implements CardServiceInterface
{
    private $httpClient;
    /**
     * @var ModelMappersCollectionInterface
     */
    private $mappers;

    public function __construct(HttpClient $httpClient, ModelMappersCollectionInterface $mappers)
    {
        $this->httpClient = $httpClient;
        $this->mappers = $mappers;
    }

    public function create(SignedRequestModel $model)
    {
        $result = $this->httpClient->post($this->mappers->getSignedRequestModelMapper()->toJson($model));
        return $this->mappers->getSignedResponseModelMapper()->toModel($result);
    }

    public function delete(SignedRequestModel $model)
    {
        $result = $this->httpClient->delete($this->mappers->getSignedRequestModelMapper()->toJson($model));
        return $result;
    }

    public function search(SearchCriteria $model)
    {
        $result = $this->httpClient->post($this->mappers->getSearchCriteriaRequestMapper()->toJson($model));
        return $this->mappers->getSearchCriteriaResponseMapper()->toModel($result);
    }

    public function get($id)
    {
        $result = $this->httpClient->get($id);
        return $this->mappers->getSignedResponseModelMapper()->toModel($result);
    }
}