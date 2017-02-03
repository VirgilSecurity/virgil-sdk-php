<?php
namespace Virgil\Sdk\Client\VirgilCards\Mapper;


/**
 * Class keeps mappers model collection for Virgil Cards Service.
 */
class ModelMappersCollection implements ModelMappersCollectionInterface
{
    /** @var SignedResponseModelMapper */
    private $responseModelMapper;

    /** @var SignedRequestModelMapper */
    private $requestModelMapper;

    /** @var SearchCriteriaResponseMapper */
    private $criteriaResponseMapper;

    /** @var SearchCriteriaRequestMapper */
    private $criteriaRequestMapper;

    /** @var ErrorResponseModelMapper */
    private $errorResponseModelMapper;


    /**
     * Class constructor.
     *
     * @param SignedResponseModelMapper    $responseModelMapper
     * @param SignedRequestModelMapper     $requestModelMapper
     * @param SearchCriteriaResponseMapper $criteriaResponseMapper
     * @param SearchCriteriaRequestMapper  $criteriaRequestMapper
     * @param ErrorResponseModelMapper     $errorResponseModelMapper
     */
    public function __construct(
        SignedResponseModelMapper $responseModelMapper,
        SignedRequestModelMapper $requestModelMapper,
        SearchCriteriaResponseMapper $criteriaResponseMapper,
        SearchCriteriaRequestMapper $criteriaRequestMapper,
        ErrorResponseModelMapper $errorResponseModelMapper
    ) {
        $this->responseModelMapper = $responseModelMapper;
        $this->requestModelMapper = $requestModelMapper;
        $this->criteriaResponseMapper = $criteriaResponseMapper;
        $this->criteriaRequestMapper = $criteriaRequestMapper;
        $this->errorResponseModelMapper = $errorResponseModelMapper;
    }


    /**
     * @return SignedRequestModelMapper
     */
    public function getSignedRequestModelMapper()
    {
        return $this->requestModelMapper;
    }


    /**
     * @return SignedResponseModelMapper
     */
    public function getSignedResponseModelMapper()
    {
        return $this->responseModelMapper;
    }


    /**
     * @return SearchCriteriaResponseMapper
     */
    public function getSearchCriteriaResponseMapper()
    {
        return $this->criteriaResponseMapper;
    }


    /**
     * @return SearchCriteriaRequestMapper
     */
    public function getSearchCriteriaRequestMapper()
    {
        return $this->criteriaRequestMapper;
    }


    /**
     * @return ErrorResponseModelMapper
     */
    public function getErrorResponseModelMapper()
    {
        return $this->errorResponseModelMapper;
    }
}
