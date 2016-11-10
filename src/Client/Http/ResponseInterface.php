<?php

namespace Virgil\SDK\Client\Http;


interface ResponseInterface
{
    /**
     * Get raw response body.
     * @return mixed
     */
    public function getBody();

    /**
     * Get raw response headers.
     * @return mixed
     */
    public function getHeaders();

    /**
     * Get HTTP response status.
     * @return StatusInterface
     */
    public function getHttpStatus();
}