<?php

namespace Virgil\SDK\Client\Card\Mapper;


use Virgil\SDK\Client\HashMapJsonMapper;

class ModelMappersCollection implements ModelMappersCollectionInterface
{
    /**
     * @var SignedResponseModelMapper
     */
    private $responseModelMapper;
    /**
     * @var SignedRequestModelMapper
     */
    private $requestModelMapper;
    /**
     * @var SearchCriteriaResponseMapper
     */
    private $criteriaResponseMapper;
    /**
     * @var SearchCriteriaRequestMapper
     */
    private $criteriaRequestMapper;
    /**
     * @var HashMapJsonMapper
     */
    private $hashMapJsonMapper;

    /**
     * ModelMappersCollection constructor.
     * @param SignedResponseModelMapper $responseModelMapper
     * @param SignedRequestModelMapper $requestModelMapper
     * @param SearchCriteriaResponseMapper $criteriaResponseMapper
     * @param SearchCriteriaRequestMapper $criteriaRequestMapper
     * @param HashMapJsonMapper $hashMapModelMapper
     */
    public function __construct(
        SignedResponseModelMapper $responseModelMapper,
        SignedRequestModelMapper $requestModelMapper,
        SearchCriteriaResponseMapper $criteriaResponseMapper,
        SearchCriteriaRequestMapper $criteriaRequestMapper,
        HashMapJsonMapper $hashMapModelMapper
    )
    {
        $this->responseModelMapper = $responseModelMapper;
        $this->requestModelMapper = $requestModelMapper;
        $this->criteriaResponseMapper = $criteriaResponseMapper;
        $this->criteriaRequestMapper = $criteriaRequestMapper;
        $this->hashMapJsonMapper = $hashMapModelMapper;
    }

    public function getSignedRequestModelMapper()
    {
        return $this->requestModelMapper;
    }

    public function getSignedResponseModelMapper()
    {
        return $this->responseModelMapper;
    }

    public function getSearchCriteriaResponseMapper()
    {
        return $this->criteriaResponseMapper;
    }

    public function getSearchCriteriaRequestMapper()
    {
        return $this->criteriaRequestMapper;
    }

    public function getHashMapJsonMapper()
    {
        return $this->hashMapJsonMapper;
    }
}