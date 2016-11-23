<?php

namespace Virgil\SDK\Client\Http;


interface ClientInterface
{
    /**
     * Make and execute HTTP POST request.
     *
     * @param string $uri
     * @param mixed $body
     * @param array $headers
     * @return ResponseInterface
     */
    public function post($uri, $body, $headers = []);

    /**
     * Make and execute HTTP DELETE request.
     *
     * @param string $uri
     * @param mixed $body
     * @param array $headers
     * @return ResponseInterface
     */
    public function delete($uri, $body, $headers = []);

    /**
     * Make and execute HTTP GET request.
     *
     * @param string $uri
     * @param array $params
     * @param array $headers
     * @return ResponseInterface
     */
    public function get($uri, $params = [], $headers = []);

    /**
     * Execute given request.
     *
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function doRequest(RequestInterface $request);

    /**
     * Get default headers for all outbound requests.
     *
     * @return array
     */
    public function getHeaders();

    /**
     * Set default headers for all outbound requests.
     *
     * @param array $headers
     */
    public function setHeaders($headers);
}