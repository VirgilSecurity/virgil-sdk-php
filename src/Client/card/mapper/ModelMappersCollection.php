<?php

namespace Virgil\SDK\Client\Card\Mapper;


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

    public function __construct(
        SignedResponseModelMapper $responseModelMapper,
        SignedRequestModelMapper $requestModelMapper,
        SearchCriteriaResponseMapper $criteriaResponseMapper,
        SearchCriteriaRequestMapper $criteriaRequestMapper
    )
    {
        $this->responseModelMapper = $responseModelMapper;
        $this->requestModelMapper = $requestModelMapper;
        $this->criteriaResponseMapper = $criteriaResponseMapper;
        $this->criteriaRequestMapper = $criteriaRequestMapper;
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
}