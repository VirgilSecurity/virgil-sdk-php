<?php
namespace Virgil\Sdk\Client\VirgilServices\VirgilRegistrationAuthority\Mapper;


use Virgil\Sdk\Client\VirgilServices\Mapper\JsonModelMapperInterface;
use Virgil\Sdk\Client\VirgilServices\Mapper\SignedRequestModelMapper;
use Virgil\Sdk\Client\VirgilServices\Mapper\SignedResponseModelMapper;

/**
 * Class keeps mappers model collection for Virgil Registration Authority Service.
 */
class ModelMappersCollection implements ModelMappersCollectionInterface
{
    /** @var SignedResponseModelMapper */
    private $signedResponseModelMapper;

    /** @var SignedRequestModelMapper */
    private $signedRequestModelMapper;

    /** @var ErrorResponseModelMapper */
    private $errorResponseModelMapper;


    /**
     * Class constructor.
     *
     * @param SignedResponseModelMapper $signedResponseModelMapper
     * @param SignedRequestModelMapper  $signedRequestModelMapper
     * @param ErrorResponseModelMapper  $errorResponseModelMapper
     */
    public function __construct(
        SignedResponseModelMapper $signedResponseModelMapper,
        SignedRequestModelMapper $signedRequestModelMapper,
        ErrorResponseModelMapper $errorResponseModelMapper
    ) {
        $this->signedResponseModelMapper = $signedResponseModelMapper;
        $this->signedRequestModelMapper = $signedRequestModelMapper;
        $this->errorResponseModelMapper = $errorResponseModelMapper;
    }


    /**
     * @return JsonModelMapperInterface
     */
    public function getSignedRequestModelMapper()
    {
        return $this->signedRequestModelMapper;
    }


    /**
     * @return JsonModelMapperInterface
     */
    public function getSignedResponseModelMapper()
    {
        return $this->signedResponseModelMapper;
    }


    /**
     * @return JsonModelMapperInterface
     */
    public function getErrorResponseModelMapper()
    {
        return $this->errorResponseModelMapper;
    }
}
