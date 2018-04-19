<?php

namespace Virgil\Http\Responses;


/**
 * Class represents HTTP response.
 * @package Virgil\Http\Responses
 */
class HttpResponse implements HttpResponseInterface
{

    /** @var string $body */
    private $body;

    /** @var string $headers */
    private $headers;

    /** @var HttpStatusCodeInterface $status */
    private $status;


    /**
     * Class constructor.
     *
     * @param HttpStatusCodeInterface $status
     * @param string                  $headers
     * @param string                  $body
     */
    public function __construct(HttpStatusCodeInterface $status, $headers, $body)
    {
        $this->body = $body;
        $this->headers = $headers;
        $this->status = $status;
    }


    /**
     * @inheritdoc
     */
    public function getBody()
    {
        return $this->body;
    }


    /**
     * @inheritdoc
     */
    public function getHeaders()
    {
        return $this->headers;
    }


    /**
     * @inheritdoc
     */
    public function getHttpStatusCode()
    {
        return $this->status;
    }
}
