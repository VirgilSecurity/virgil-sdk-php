<?php
namespace Virgil\Sdk\Client\VirgilCards\Model;


use Virgil\Sdk\Client\VirgilCards\CardsErrorMessages;

/**
 * Class provides error response information.
 */
class ErrorResponseModel
{
    /** @var null|string */
    private $errorCode;


    /**
     * Class constructor.
     *
     * @param string $errorCode in some cases service does not returns error code.
     */
    public function __construct($errorCode = null)
    {
        $this->errorCode = $errorCode;
    }


    /**
     * Returns error message or default.
     *
     * @param string $defaultMessage
     *
     * @return string
     */
    public function getMessageOrDefault($defaultMessage = 'unspecified error')
    {
        if ($this->isEmpty()) {
            return $defaultMessage;
        }

        return CardsErrorMessages::getMessage($this->errorCode);
    }


    /**
     * Returns error code.
     *
     * @return string
     */
    public function getCode()
    {
        return $this->errorCode;
    }


    /**
     * Checks if model is empty.
     *
     * @return bool
     */
    public function isEmpty()
    {
        return $this->errorCode === null;
    }
}
