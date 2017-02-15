<?php
namespace Virgil\Sdk\Client\VirgilServices\VirgilIdentity\Model;


/**
 * Class represents confirm response model.
 */
class ConfirmResponseModel
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
}
