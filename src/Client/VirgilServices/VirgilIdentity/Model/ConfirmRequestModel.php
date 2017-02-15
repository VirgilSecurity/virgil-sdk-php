<?php
namespace Virgil\Sdk\Client\VirgilServices\VirgilIdentity\Model;


use Virgil\Sdk\Client\VirgilServices\Model\AbstractModel;

use Virgil\Sdk\Client\VirgilServices\VirgilIdentity\Constants\JsonProperties;

/**
 * Class represents the confirm identity request model.
 */
class ConfirmRequestModel extends AbstractModel
{
    /** @var string */
    private $confirmationCode;

    /** @var string */
    private $actionId;

    /** @var null|TokenModel */
    private $token;


    /**
     * Class constructor.
     *
     * @param string          $confirmationCode
     * @param string          $actionId
     * @param TokenModel|null $token
     */
    public function __construct($confirmationCode, $actionId, TokenModel $token = null)
    {
        $this->confirmationCode = $confirmationCode;
        $this->actionId = $actionId;
        $this->token = $token;
    }


    /**
     * @return string
     */
    public function getConfirmationCode()
    {
        return $this->confirmationCode;
    }


    /**
     * @return string
     */
    public function getActionId()
    {
        return $this->actionId;
    }


    /**
     * @return null|TokenModel
     */
    public function getToken()
    {
        return $this->token;
    }


    /**
     * @inheritdoc
     */
    protected function jsonSerializeData()
    {
        return [
            JsonProperties::CONFIRMATION_CODE_ATTRIBUTE_NAME => $this->confirmationCode,
            JsonProperties::ACTION_ID_ATTRIBUTE_NAME         => $this->actionId,
            JsonProperties::TOKEN_ATTRIBUTE_NAME             => $this->token,
        ];
    }
}
