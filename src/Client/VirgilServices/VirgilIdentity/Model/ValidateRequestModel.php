<?php
namespace Virgil\Sdk\Client\VirgilServices\VirgilIdentity\Model;


use JsonSerializable;

use Virgil\Sdk\Client\VirgilServices\VirgilIdentity\Constants\JsonProperties;

/**
 * Class represents the validate identity request model.
 */
class ValidateRequestModel implements JsonSerializable
{
    /** @var string */
    private $type;

    /** @var string */
    private $value;

    /** @var string */
    private $validationToken;


    /**
     * Class constructor.
     *
     * @param string $type
     * @param string $value
     * @param string $validationToken
     */
    public function __construct($type, $value, $validationToken)
    {
        $this->type = $type;
        $this->value = $value;
        $this->validationToken = $validationToken;
    }


    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }


    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }


    /**
     * @return string
     */
    public function getValidationToken()
    {
        return $this->validationToken;
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
        return [
            JsonProperties::TYPE_ATTRIBUTE_NAME             => $this->type,
            JsonProperties::VALUE_ATTRIBUTE_NAME            => $this->value,
            JsonProperties::VALIDATION_TOKEN_ATTRIBUTE_NAME => $this->validationToken,
        ];
    }
}
