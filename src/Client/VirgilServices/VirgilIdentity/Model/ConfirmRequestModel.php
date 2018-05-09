<?php
namespace Virgil\Sdk\Client\VirgilServices\VirgilIdentity\Model;


use JsonSerializable;

use Virgil\Sdk\Client\VirgilServices\VirgilIdentity\Constants\JsonProperties;

/**
 * Class represents the confirm identity request model.
 */
class ConfirmRequestModel implements JsonSerializable
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
     * Specify data which should be serialized to JSON
     * @link  http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        $data = [
            JsonProperties::CONFIRMATION_CODE_ATTRIBUTE_NAME => $this->confirmationCode,
            JsonProperties::ACTION_ID_ATTRIBUTE_NAME         => $this->actionId,
        ];

        if ($this->token != null && count($this->token->jsonSerialize())) {
            $data[JsonProperties::TOKEN_ATTRIBUTE_NAME] = $this->token;
        }

        return $data;
    }
}
