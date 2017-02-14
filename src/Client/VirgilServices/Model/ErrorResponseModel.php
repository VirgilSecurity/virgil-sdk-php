<?php

namespace Virgil\Sdk\Client\VirgilServices\Model;


/**
 * Class keeps information of errors response from Virgil services.
 */
class ErrorResponseModel
{
    /** @var null|string */
    private $code;

    /** @var null|string */
    private $message;


    /**
     * Class constructor.
     *
     * @param string $code
     * @param string $message
     */
    public function __construct($code = null, $message = null)
    {
        $this->code = $code;
        $this->message = $message;
    }


    /**
     * Returns service error code.
     *
     * @return null|string
     */
    public function getCode()
    {
        return $this->code;
    }


    /**
     * Returns error message that defined by service error code.
     *
     * @return null|string
     */
    public function getMessage()
    {
        return $this->message;
    }
}
