<?php
namespace Virgil\Sdk\Client\VirgilServices\VirgilCards\Mapper;


use Virgil\Sdk\Client\VirgilServices\Mapper\SignedRequestModelMapper;
use Virgil\Sdk\Client\VirgilServices\Mapper\SignedResponseModelMapper;
use Virgil\Sdk\Client\VirgilServices\Mapper\SignedResponseModelsMapper;

/**
 * Class keeps mappers model collection for Virgil Cards Service.
 */
class ModelMappersCollection implements ModelMappersCollectionInterface
{
    /** @var SignedResponseModelMapper */
    private $signedResponseModelMapper;

    /** @var SignedRequestModelMapper */
    private $signedRequestModelMapper;

    /** @var SignedResponseModelsMapper */
    private $signedResponseModelsMapper;

    /** @var SearchRequestModelMapper */
    private $searchRequestModelMapper;

    /** @var ErrorResponseModelMapper */
    private $errorResponseModelMapper;


    /**
     * Class constructor.
     *
     * @param SignedResponseModelMapper  $signedResponseModelMapper
     * @param SignedRequestModelMapper   $signedRequestModelMapper
     * @param SignedResponseModelsMapper $signedResponseModelsMapper
     * @param SearchRequestModelMapper   $searchRequestModelMapper
     * @param ErrorResponseModelMapper   $errorResponseModelMapper
     */
    public function __construct(
        SignedResponseModelMapper $signedResponseModelMapper,
        SignedRequestModelMapper $signedRequestModelMapper,
        SignedResponseModelsMapper $signedResponseModelsMapper,
        SearchRequestModelMapper $searchRequestModelMapper,
        ErrorResponseModelMapper $errorResponseModelMapper
    ) {
        $this->signedResponseModelMapper = $signedResponseModelMapper;
        $this->signedRequestModelMapper = $signedRequestModelMapper;
        $this->signedResponseModelsMapper = $signedResponseModelsMapper;
        $this->searchRequestModelMapper = $searchRequestModelMapper;
        $this->errorResponseModelMapper = $errorResponseModelMapper;
    }


    /**
     * @return SignedRequestModelMapper
     */
    public function getSignedRequestModelMapper()
    {
        return $this->signedRequestModelMapper;
    }


    /**
     * @return SignedResponseModelMapper
     */
    public function getSignedResponseModelMapper()
    {
        return $this->signedResponseModelMapper;
    }


    /**
     * @return SignedResponseModelsMapper
     */
    public function getSignedResponseModelsMapper()
    {
        return $this->signedResponseModelsMapper;
    }


    /**
     * @return SearchRequestModelMapper
     */
    public function getSearchRequestModelMapper()
    {
        return $this->searchRequestModelMapper;
    }


    /**
     * @return ErrorResponseModelMapper
     */
    public function getErrorResponseModelMapper()
    {
        return $this->errorResponseModelMapper;
    }
}
