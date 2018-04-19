<?php

namespace Virgil\Http;


use Virgil\Http\Requests\HttpRequestInterface;
use Virgil\Http\Responses\HttpResponseInterface;

/**
 * Interface provides HTTP client request.
 * @package Virgil\Http
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
     * @return HttpResponseInterface
     */
    public function post($requestUrl, $requestBody, array $requestHeaders = []);


    /**
     * Make and execute a HTTP DELETE request.
     *
     * @param string $requestUrl
     * @param mixed  $requestBody
     * @param array  $requestHeaders
     *
     * @return HttpResponseInterface
     */
    public function delete($requestUrl, $requestBody, array $requestHeaders = []);


    /**
     * Make and execute a HTTP GET request.
     *
     * @param string $requestUrl
     * @param array  $requestParams
     * @param array  $requestHeaders
     *
     * @return HttpResponseInterface
     */
    public function get($requestUrl, array $requestParams = [], array $requestHeaders = []);


    /**
     * Sends http request.
     *
     * @param HttpRequestInterface $httpRequest
     *
     * @return HttpResponseInterface
     */
    public function send(HttpRequestInterface $httpRequest);


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
