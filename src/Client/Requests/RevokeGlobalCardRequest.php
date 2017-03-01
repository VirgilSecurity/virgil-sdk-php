<?php
namespace Virgil\Sdk\Client\Requests;


use Virgil\Sdk\Client\VirgilServices\Model\RevokeCardContentModel;
use Virgil\Sdk\Client\VirgilServices\Model\SignedRequestMetaModel;
use Virgil\Sdk\Client\VirgilServices\Model\SignedRequestModel;
use Virgil\Sdk\Client\VirgilServices\Model\ValidationModel;

/**
 * Class represents request for global card revoking.
 */
class RevokeGlobalCardRequest extends RevokeCardRequest
{
    /** @var ValidationModel */
    private $validation;


    /**
     * Class constructor.
     *
     * @param string          $id
     * @param string          $reason
     * @param ValidationModel $validation
     */
    public function __construct($id, $reason, ValidationModel $validation = null)
    {
        parent::__construct($id, $reason);

        $this->validation = $validation;
    }


    /**
     * Builds a revoke global request from request model.
     *
     * @param SignedRequestModel $signedRequestModel
     *
     * @return RevokeGlobalCardRequest
     */
    protected static function buildRequestFromRequestModel(SignedRequestModel $signedRequestModel)
    {
        /** @var RevokeCardContentModel $requestContentModel */
        $requestContentModel = $signedRequestModel->getRequestContent();

        $requestMetaModel = $signedRequestModel->getRequestMeta();

        return new self(
            $requestContentModel->getId(),
            $requestContentModel->getRevocationReason(),
            $requestMetaModel->getValidation()
        );
    }


    /**
     * @return ValidationModel
     */
    public function getValidation()
    {
        return $this->validation;
    }


    /**
     * @inheritdoc
     */
    protected function getCardMeta()
    {
        $cardMeta = parent::getCardMeta();

        return new SignedRequestMetaModel($cardMeta->getSigns(), $this->validation);
    }
}
