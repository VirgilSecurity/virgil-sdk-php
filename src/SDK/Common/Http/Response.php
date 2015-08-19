<?php

namespace Virgil\SDK\Common\Http;

use GuzzleHttp\Message\ResponseInterface as HttpResponseInterface;

class Response implements ResponseInterface {

    protected $_body       = null;
    protected $_statusCode = null;
    protected $_headers    = array();

    public function __construct(HttpResponseInterface $response) {

        $this->_statusCode = $response->getStatusCode();
        $this->_headers    = $response->getHeaders();

        $this->_body       = $response->json();
    }

    public function getBody() {

        return $this->_body;
    }

    public function getHeaders() {

        return $this->_headers;
    }

    public function getHttpStatusCode() {

        return $this->_statusCode;
    }

}