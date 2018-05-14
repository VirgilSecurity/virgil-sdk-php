<?php

namespace Virgil\Http;


use RuntimeException;

use Virgil\Http\Constants\RequestMethods;

use Virgil\Http\Requests\HttpRequestInterface;

/**
 * An abstract http client class responsible for defining send request strategy logic.
 * @package Virgil\Http
 */
abstract class AbstractHttpClient implements HttpClientInterface
{
    /**
     * @inheritdoc
     */
    public function send(HttpRequestInterface $httpRequest)
    {
        switch ($httpRequest->getMethod()) {
            case RequestMethods::HTTP_GET:
                return $this->get($httpRequest->getUrl(), [], $httpRequest->getHeaders());
            case RequestMethods::HTTP_POST:
                return $this->post($httpRequest->getUrl(), $httpRequest->getBody(), $httpRequest->getHeaders());
            case  RequestMethods::HTTP_DELETE:
                return $this->delete($httpRequest->getUrl(), $httpRequest->getBody(), $httpRequest->getHeaders());
        }

        throw new RuntimeException('No such methods for handling this kind of request');
    }
}
