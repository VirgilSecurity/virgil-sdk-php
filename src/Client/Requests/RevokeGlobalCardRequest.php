<?php
namespace Virgil\Sdk\Client\Requests;


use Virgil\Sdk\Client\VirgilServices\Model\SignedRequestMetaModel;
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
