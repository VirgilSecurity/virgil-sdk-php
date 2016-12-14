<?php
namespace Virgil\Sdk\Client\Http;


/**
 * Interface represents HTTP response.
 */
interface ResponseInterface
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
