<?php
namespace Virgil\Sdk\Client\VirgilServices\VirgilIdentity\Model;


use JsonSerializable;

use Virgil\Sdk\Client\VirgilServices\VirgilIdentity\Constants\JsonProperties;

/**
 * Class represents the verify identity request model.
 */
class VerifyRequestModel implements JsonSerializable
{
    /** @var string */
    private $identityType;

    /** @var string */
    private $identity;

    /** @var array */
    private $extraFields;


    /**
     * Class constructor.
     *
     * @param string $identityType
     * @param string $identity
     * @param array  $extraFields
     */
    public function __construct($identityType, $identity, array $extraFields = [])
    {
        $this->identityType = $identityType;
        $this->identity = $identity;
        $this->extraFields = $extraFields;
    }


    /**
     * @return string
     */
    public function getIdentityType()
    {
        return $this->identityType;
    }


    /**
     * @return string
     */
    public function getIdentity()
    {
        return $this->identity;
    }


    /**
     * @return array
     */
    public function getExtraFields()
    {
        return $this->extraFields;
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
            JsonProperties::TYPE_ATTRIBUTE_NAME  => $this->identityType,
            JsonProperties::VALUE_ATTRIBUTE_NAME => $this->identity,
        ];

        if (count($this->extraFields)) {
            $data[JsonProperties::EXTRA_FIELDS_ATTRIBUTE_NAME] = $this->extraFields;
        }

        return $data;
    }
}
