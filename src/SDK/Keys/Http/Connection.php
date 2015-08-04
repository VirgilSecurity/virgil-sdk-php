<?php

namespace Virgil\SDK\Keys\Http;

use GuzzleHttp\Client,
    GuzzleHttp\Message\ResponseInterface as HttpResponseInterface,
    Virgil\SDK\Common\Http\RequestInterface,
    Virgil\SDK\Common\Http\Response,
    Virgil\SDK\Common\Http\ResponseInterface,
    Virgil\SDK\Keys\Exceptions\PkiWebException,
    Virgil\SDK\Keys\Http\Error;

class Connection implements ConnectionInterface {

    protected $_baseUrl        = null;
    protected $_appToken       = null;
    protected $_apiVersion     = 'v2';
    protected $_defaultHeaders = array(
        'Content-Type' => 'application/json'
    );

    public function __construct($appToken, $baseUrl, $apiVersion = null) {

        $this->_appToken = $appToken;
        $this->_baseUrl  = $baseUrl;

        if($apiVersion !== null) {
            $this->_apiVersion = $apiVersion;
        }
    }

    /**
     * @return string
     */
    public function getBaseUrl() {

        return $this->_baseUrl . '/{version}/';
    }

    /**
     * @return string
     */
    public function getAppToken() {

        return $this->_appToken;
    }

    public function getApiVersion() {

        return $this->_apiVersion;
    }

    /**
     * @param RequestInterface $request
     * @return Response
     */
    public function send(RequestInterface $request) {

        $httpClient = new Client(array(
            'base_url' => array(
                $this->getBaseUrl(),
                array(
                    'version' => $this->getApiVersion()
                )
            )
        ));

        $options = array(
            'exceptions' => false,
            'headers'    => $this->_getHeaders()
        );

        if($request->isBodyEmpty() == false) {
            $options['json'] = $request->getBody();
        }

        $httpRequest = $httpClient->createRequest(
            $request->getRequestMethod(),
            $request->getEndpoint(),
            $options
        );

        $httpResponse = $httpClient->send(
            $httpRequest
        );

        if($this->isSuccessHttpStatus($httpResponse) !== true) {
            $this->exceptionHandler(
                $httpResponse
            );
        }

        return new Response(
            $httpResponse
        );
    }

    private function _getHeaders() {

        $headers = $this->_defaultHeaders;

        if($this->getAppToken() !== null) {
            $headers['X-VIRGIL-APP-TOKEN'] = $this->getAppToken();
        }

        return $headers;
    }

    private function isSuccessHttpStatus(HttpResponseInterface $httpResponse) {

        return $httpResponse->getStatusCode() == ResponseInterface::HTTP_CODE_OK;
    }

    private function exceptionHandler(HttpResponseInterface $httpResponse) {
        $data      = $httpResponse->json();
        $errorCode = 0;

        if(!empty($data['error'])) {
            $errorCode = $data['error']['code'];
        }

        $errorMessage = Error\Error::getHttpErrorMessage(
            $httpResponse->getStatusCode(),
            $errorCode,
            'Undefined exception: ' . $errorCode . '; Http status: ' . $httpResponse->getStatusCode()
        );

        throw new PkiWebException(
            $errorCode,
            $errorMessage,
            $httpResponse->getStatusCode()
        );
    }
}