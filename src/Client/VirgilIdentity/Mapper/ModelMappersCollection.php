<?php
namespace Virgil\Sdk\Client\VirgilIdentity\Mapper;


use Virgil\Sdk\Client\VirgilCards\Mapper\ErrorResponseModelMapper;

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
    private $confirmRequestModelMappe;

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
     * @param ConfirmRequestModelMapper  $confirmRequestModelMappe
     * @param ConfirmResponseModelMapper $confirmResponseModelMapper
     * @param ValidateRequestModelMapper $validateRequestModelMapper
     * @param ErrorResponseModelMapper   $errorResponseModelMapper
     */
    public function __construct(
        VerifyRequestModelMapper $verifyRequestModelMapper,
        VerifyResponseModelMapper $verifyResponseModelMapper,
        ConfirmRequestModelMapper $confirmRequestModelMappe,
        ConfirmResponseModelMapper $confirmResponseModelMapper,
        ValidateRequestModelMapper $validateRequestModelMapper,
        ErrorResponseModelMapper $errorResponseModelMapper
    ) {
        $this->verifyRequestModelMapper = $verifyRequestModelMapper;
        $this->verifyResponseModelMapper = $verifyResponseModelMapper;
        $this->confirmRequestModelMappe = $confirmRequestModelMappe;
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
        return $this->confirmRequestModelMappe;
    }


    /**
     * @return ValidateRequestModelMapper
     */
    public function getValidateRequestModelMapper()
    {
        return $this->validateRequestModelMapper;
    }
}
