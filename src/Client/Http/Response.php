<?php
namespace Virgil\Sdk\Client\Http;


class Response implements ResponseInterface
{
    private $body;
    private $headers;
    private $status;


    /**
     * Response constructor.
     *
     * @param StatusInterface $status
     * @param                 $headers
     * @param                 $body
     */
    public function __construct(StatusInterface $status, $headers, $body)
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
    public function getHttpStatus()
    {
        return $this->status;
    }
}
