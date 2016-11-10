<?php

namespace Virgil\SDK\Client\Http;


class Response implements ResponseInterface
{
    private $body;
    private $headers;
    private $status;

    /**
     * Response constructor.
     * @param StatusInterface $status
     * @param $headers
     * @param $body
     */
    public function __construct(StatusInterface $status, $headers, $body)
    {
        $this->body = $body;
        $this->headers = $headers;
        $this->status = $status;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function getHttpStatus()
    {
        return $this->status;
    }
}