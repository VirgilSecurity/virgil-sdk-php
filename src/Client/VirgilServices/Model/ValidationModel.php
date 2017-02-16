<?php
namespace Virgil\Sdk\Client\VirgilServices\Model;


use Virgil\Sdk\Client\VirgilServices\Constants\JsonProperties;


/**
 * Class represents json serializable validation card model.
 */
class ValidationModel extends AbstractModel
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
     * @inheritdoc
     */
    protected function jsonSerializeData()
    {
        return [
            JsonProperties::TOKEN_ATTRIBUTE_NAME => $this->token,
        ];
    }
}
