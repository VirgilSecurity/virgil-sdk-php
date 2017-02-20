<?php
namespace Virgil\Sdk\Client\VirgilServices;


use Exception;

use Virgil\Sdk\Exceptions\VirgilException;

/**
 * Class specifies exception if one of Virgil Services returns any error codes or response status code is unsuccessful.
 */
class UnsuccessfulResponseException extends VirgilException
{
    /** @var string */
    private $httpStatusCode;

    /** @var string */
    private $serviceErrorCode;


    /**
     * Class constructor.
     *
     * @param string         $message
     * @param string         $httpStatusCode
     * @param string         $serviceErrorCode
     * @param Exception|null $previous
     */
    public function __construct($message, $httpStatusCode, $serviceErrorCode, Exception $previous = null)
    {
        parent::__construct($message, $httpStatusCode, $previous);

        $this->httpStatusCode = $httpStatusCode;

        $this->serviceErrorCode = $serviceErrorCode;
    }


    /**
     * Returns the service error code.
     *
     * @return string
     */
    public function getServiceErrorCode()
    {
        return $this->serviceErrorCode;
    }


    /**
     * Returns the service http status code.
     *
     * @return string
     */
    public function getHttpStatusCode()
    {
        return $this->httpStatusCode;
    }
}
