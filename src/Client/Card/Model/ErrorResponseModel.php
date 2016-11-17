<?php

namespace Virgil\SDK\Client\Card\Model;


use Virgil\SDK\Client\Card\CardsErrorMessages;

class ErrorResponseModel
{
    private $errorCode;

    /**
     * ErrorResponse constructor.
     * @param string $errorCode
     */
    public function __construct($errorCode = null)
    {
        $this->errorCode = $errorCode;
    }

    /**
     * Gets error message or return default.
     * @param string $defaultMessage
     * @return string
     */
    public function getMessageOrDefault($defaultMessage = 'unspecified error')
    {
        if($this->isEmpty()) {
            return $defaultMessage;
        }

        return CardsErrorMessages::getMessage($this->errorCode);
    }

    /**
     * Gets error code.
     * @return string
     */
    public function getCode()
    {
        return $this->errorCode;
    }

    /**
     * Checks if model is empty.
     * @return bool
     */
    public function isEmpty()
    {
        return $this->errorCode === null;
    }
}