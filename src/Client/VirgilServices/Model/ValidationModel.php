<?php
namespace Virgil\Sdk\Client\VirgilServices\Model;


use JsonSerializable;

use Virgil\Sdk\Client\VirgilServices\Constants\JsonProperties;


/**
 * Class represents json serializable validation card model.
 */
class ValidationModel implements JsonSerializable
{
    /** @var string */
    private $token;


    /**
     * Class constructor.
     *
     * @param string $token
     */
    public function __construct($token)
    {
        $this->token = $token;
    }


    /**
     * @return string
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
        return [
            JsonProperties::TOKEN_ATTRIBUTE_NAME => $this->token,
        ];
    }
}
