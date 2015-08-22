<?php

namespace Virgil\SDK\PrivateKeys\Http;

use GuzzleHttp\Client,
    GuzzleHttp\Message\ResponseInterface as HttpResponseInterface,
    Virgil\SDK\Common\Http\RequestInterface,
    Virgil\SDK\Common\Http\Response,
    Virgil\SDK\Common\Http\ResponseInterface,
    Virgil\SDK\PrivateKeys\Exception\WebException,
    Virgil\SDK\PrivateKeys\Http\Error;

class Connection implements ConnectionInterface {

    protected $_baseUrl        = null;
    protected $_apiVersion     = 'v2';

    protected $_authToken      = null;

    protected $_headers        = array();
    protected $_defaultHeaders = array(
        'Content-Type' => 'application/json'
    );

    protected $_userName       = null;
    protected $_userPassword   = null;

    public function __construct($baseUrl, $apiVersion = null) {

        $this->_baseUrl = $baseUrl;

        if($apiVersion !== null) {
            $this->_apiVersion = $apiVersion;
        }
    }

    public function setAuthCredentials($userName, $userPassword) {

        $this->_userName = $userName;
        $this->_userPassword = $userPassword;
    }

    public function setHeaders($headers) {

        $this->_headers = array_merge(
            $this->_headers,
            $headers
        );

        return $this;
    }

    public function getBaseUrl() {

        return $this->_baseUrl . '/{version}/';
    }

    public function getApiVersion() {

        return $this->_apiVersion;
    }

    public function getAuthToken() {

        return $this->_authToken;
    }

    public function send(RequestInterface $request) {

        if($this->_isCredentionalsProvided() && !$this->_isAuthenticated()) {
            $this->_authToken = $this->_authenticate();
        }

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

        $headers = array_merge(
            $this->_defaultHeaders,
            $this->_headers
        );

        if($this->_isCredentionalsProvided() && $this->_isAuthenticated()) {
            $headers['X-VIRGIL-AUTHENTICATION'] = $this->getAuthToken();
        }

        return $headers;
    }

    private function _getUserName() {

        return $this->_userName;
    }

    private function _getPassword() {

        return $this->_userPassword;
    }

    private function _isCredentionalsProvided() {

        return !is_null($this->_userName) && !is_null($this->_userPassword);
    }

    private function _isAuthenticated() {

        return $this->_authToken !== null;
    }

    private function _authenticate() {

        $httpClient = new Client(array(
            'base_url' => array(
                $this->getBaseUrl(),
                array(
                    'version' => $this->getApiVersion()
                )
            )
        ));

        $httpRequest = $httpClient->createRequest(
            'POST',
            'authentication/get-token',
            array(
                'exceptions'   => false,
                'headers'      => $this->_getHeaders(),
                'json'         => array(
                    'password' => $this->_getPassword(),
                    'user_data'   => array(
                        'class'   => 'user_id',
                        'type'    => 'email',
                        'value'   => $this->_getUserName()
                    )
                )
            )
        );

        $httpResponse = $httpClient->send(
            $httpRequest
        );

        if($this->isSuccessHttpStatus($httpResponse) !== true) {
            $this->exceptionHandler(
                $httpResponse
            );
        }

        return $httpResponse->json()['auth_token'];
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

        throw new WebException(
            $errorCode,
            $errorMessage,
            $httpResponse->getStatusCode()
        );
    }
}