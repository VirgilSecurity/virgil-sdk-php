<?php
namespace Virgil\Sdk\Client\Http\Responses;


/**
 * Class represents HTTP status code.
 */
class HttpStatusCode implements HttpStatusCodeInterface
{
    /** @var string $statusCode */
    private $statusCode;


    /**
     * Class constructor.
     *
     * @param string $statusCode
     */
    public function __construct($statusCode)
    {
        $this->statusCode = $statusCode;
    }


    /**
     * @inheritdoc
     */
    public function isSuccess()
    {
        return strval($this->statusCode)[0] === '2';
    }


    /**
     * @inheritdoc
     */
    public function getCode()
    {
        return $this->statusCode;
    }
}
