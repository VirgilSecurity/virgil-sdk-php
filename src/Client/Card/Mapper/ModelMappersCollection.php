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
    /**
     * @var ErrorResponseModelMapper
     */
    private $errorResponseModelMapper;

    /**
     * ModelMappersCollection constructor.
     * @param SignedResponseModelMapper $responseModelMapper
     * @param SignedRequestModelMapper $requestModelMapper
     * @param SearchCriteriaResponseMapper $criteriaResponseMapper
     * @param SearchCriteriaRequestMapper $criteriaRequestMapper
     * @param ErrorResponseModelMapper $errorResponseModelMapper
     */
    public function __construct(
        SignedResponseModelMapper $responseModelMapper,
        SignedRequestModelMapper $requestModelMapper,
        SearchCriteriaResponseMapper $criteriaResponseMapper,
        SearchCriteriaRequestMapper $criteriaRequestMapper,
        ErrorResponseModelMapper $errorResponseModelMapper)
    {
        $this->responseModelMapper = $responseModelMapper;
        $this->requestModelMapper = $requestModelMapper;
        $this->criteriaResponseMapper = $criteriaResponseMapper;
        $this->criteriaRequestMapper = $criteriaRequestMapper;
        $this->errorResponseModelMapper = $errorResponseModelMapper;
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

    public function getErrorResponseModelMapper()
    {
        return $this->errorResponseModelMapper;
    }
}