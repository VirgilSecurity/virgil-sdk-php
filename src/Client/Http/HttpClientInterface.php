<?php
namespace Virgil\Sdk\Client\Http;


/**
 * Interface provides HTTP client request.
 */
interface HttpClientInterface
{
    /**
     * Make and execute a HTTP POST request.
     *
     * @param string $requestUrl
     * @param mixed  $requestBody
     * @param array  $requestHeaders
     *
     * @return ResponseInterface
     */
    public function post($requestUrl, $requestBody, array $requestHeaders = []);


    /**
     * Make and execute a HTTP DELETE request.
     *
     * @param string $requestUrl
     * @param mixed  $requestBody
     * @param array  $requestHeaders
     *
     * @return ResponseInterface
     */
    public function delete($requestUrl, $requestBody, array $requestHeaders = []);


    /**
     * Make and execute a HTTP GET request.
     *
     * @param string $requestUrl
     * @param array  $requestParams
     * @param array  $requestHeaders
     *
     * @return ResponseInterface
     */
    public function get($requestUrl, array $requestParams = [], array $requestHeaders = []);


    /**
     * Get default headers for all requests.
     *
     * @return array
     */
    public function getRequestHeaders();


    /**
     * Set default headers for all requests.
     *
     * @param array $requestHeaders
     */
    public function setRequestHeaders($requestHeaders);
}
