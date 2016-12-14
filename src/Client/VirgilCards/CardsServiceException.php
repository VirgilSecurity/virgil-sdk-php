<?php
namespace Virgil\Sdk\Client\VirgilCards;


use Exception;
use Virgil\Sdk\VirgilException;

/**
 * Class specifies exception if Virgil Cards Service returns any error codes.
 */
class CardsServiceException extends VirgilException
{
    /** @var string $serviceErrorCode */
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

        $this->serviceErrorCode = $serviceErrorCode;
    }


    /**
     * Returns service error code.
     *
     * @return string
     */
    public function getServiceErrorCode()
    {
        return $this->serviceErrorCode;
    }
}
