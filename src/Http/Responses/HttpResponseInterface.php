<?php

namespace Virgil\Sdk\Http\Responses;

/**
 * Interface represents HTTP response.
 * @package Virgil\Http\Responses
 */
interface HttpResponseInterface
{
    /**
     * Get raw response body.
     *
     * @return string
     */
    public function getBody();


    /**
     * Get raw response headers.
     *
     * @return string
     */
    public function getHeaders();


    /**
     * Get HTTP response status.
     *
     * @return HttpStatusCodeInterface
     */
    public function getHttpStatusCode();
}
