<?php
namespace Virgil\Sdk\Client\VirgilServices\VirgilIdentity\Mapper;


/**
 * Class keeps mappers model collection for Virgil Identity Service.
 */
class ModelMappersCollection implements ModelMappersCollectionInterface
{
    /** @var VerifyRequestModelMapper */
    private $verifyRequestModelMapper;

    /** @var VerifyResponseModelMapper */
    private $verifyResponseModelMapper;

    /** @var ConfirmRequestModelMapper */
    private $confirmRequestModelMapper;

    /** @var ConfirmResponseModelMapper */
    private $confirmResponseModelMapper;

    /** @var ValidateRequestModelMapper */
    private $validateRequestModelMapper;

    /** @var ErrorResponseModelMapper */
    private $errorResponseModelMapper;


    /**
     * Class constructor.
     *
     * @param VerifyRequestModelMapper   $verifyRequestModelMapper
     * @param VerifyResponseModelMapper  $verifyResponseModelMapper
     * @param ConfirmRequestModelMapper  $confirmRequestModelMapper
     * @param ConfirmResponseModelMapper $confirmResponseModelMapper
     * @param ValidateRequestModelMapper $validateRequestModelMapper
     * @param ErrorResponseModelMapper   $errorResponseModelMapper
     */
    public function __construct(
        VerifyRequestModelMapper $verifyRequestModelMapper,
        VerifyResponseModelMapper $verifyResponseModelMapper,
        ConfirmRequestModelMapper $confirmRequestModelMapper,
        ConfirmResponseModelMapper $confirmResponseModelMapper,
        ValidateRequestModelMapper $validateRequestModelMapper,
        ErrorResponseModelMapper $errorResponseModelMapper
    ) {
        $this->verifyRequestModelMapper = $verifyRequestModelMapper;
        $this->verifyResponseModelMapper = $verifyResponseModelMapper;
        $this->confirmRequestModelMapper = $confirmRequestModelMapper;
        $this->confirmResponseModelMapper = $confirmResponseModelMapper;
        $this->validateRequestModelMapper = $validateRequestModelMapper;
        $this->errorResponseModelMapper = $errorResponseModelMapper;
    }


    /**
     * @return VerifyResponseModelMapper
     */
    public function getVerifyResponseModelMapper()
    {
        return $this->verifyResponseModelMapper;
    }


    /**
     * @return ConfirmResponseModelMapper
     */
    public function getConfirmResponseModelMapper()
    {
        return $this->confirmResponseModelMapper;
    }


    /**
     * @return ErrorResponseModelMapper
     */
    public function getErrorResponseModelMapper()
    {
        return $this->errorResponseModelMapper;
    }


    /**
     * @return VerifyRequestModelMapper
     */
    public function getVerifyRequestModelMapper()
    {
        return $this->verifyRequestModelMapper;
    }


    /**
     * @return ConfirmRequestModelMapper
     */
    public function getConfirmRequestModelMapper()
    {
        return $this->confirmRequestModelMapper;
    }


    /**
     * @return ValidateRequestModelMapper
     */
    public function getValidateRequestModelMapper()
    {
        return $this->validateRequestModelMapper;
    }
}
