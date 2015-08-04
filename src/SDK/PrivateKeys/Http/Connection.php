<?php

namespace Virgil\SDK\PrivateKeys\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Message\ResponseInterface as HttpResponseInterface;
use Virgil\SDK\Common\Http\RequestInterface;
use Virgil\SDK\Common\Http\Response;
use Virgil\SDK\Common\Http\ResponseInterface;
use Virgil\SDK\PrivateKeys\Exception\KeyringWebException;
use Virgil\SDK\PrivateKeys\Http\Error;

class Connection implements ConnectionInterface {

    protected $_baseUrl        = null;
    protected $_credentials    = array();
    protected $_apiVersion     = 'v2';
    protected $_authToken      = null;
    protected $_defaultHeaders = array(
        'Content-Type' => 'application/json'
    );

    public function __construct($baseUrl, $apiVersion = null, $credentials = array()) {
        $this->_baseUrl = $baseUrl;

        if($apiVersion !== null) {
            $this->_apiVersion = $apiVersion;
        }

        if(isset($credentials['username']) && isset($credentials['password'])) {
            $this->_credentials = $credentials;
        }
    }

    public function setCredentials($username, $password) {
        $this->_credentials = array(
            'username' => $username,
            'password' => $password
        );
    }

    /**
     * @return string
     */
    public function getBaseUrl() {
        return $this->_baseUrl . '/{version}/';
    }

    public function getAuthToken() {
        return $this->_authToken;
    }

    public function getApiVersion() {
        return $this->_apiVersion;
    }

    /**
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function send(RequestInterface $request) {
        if($this->_isAuthenticated() == false && $this->_areCredentialsSet() == true) {
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

        $httpRequest = $httpClient->createRequest($request->getRequestMethod(), $request->getEndpoint(), $options);

        $httpResponse = $httpClient->send($httpRequest);

        if($this->isSuccessHttpStatus($httpResponse) !== true) {
            $this->exceptionHandler($httpResponse);
        }

        return new Response($httpResponse);
    }

    private function _getHeaders() {
        $headers = $this->_defaultHeaders;

        if($this->getAuthToken() !== null) {
            $headers['X-AUTH-TOKEN'] = $this->getAuthToken();
        }

        return $headers;
    }

    private function _getUserName() {
        return isset($this->_credentials['username']) ? $this->_credentials['username'] : null;
    }

    private function _getPassword() {
        return isset($this->_credentials['password']) ? $this->_credentials['password'] : null;
    }

    private function _isAuthenticated() {
        return $this->_authToken !== null;
    }

    private function _areCredentialsSet() {
        return !empty($this->_credentials);
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

        $httpRequest = $httpClient->createRequest('POST', 'authentication/get-token', array(
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
        ));

        $httpResponse = $httpClient->send($httpRequest);

        if($this->isSuccessHttpStatus($httpResponse) !== true) {
            $this->exceptionHandler($httpResponse);
        }

        return $httpResponse->json(array('object' => true))->auth_token;
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

        $errorMessage = Error\Error::getHttpErrorMessage($httpResponse->getStatusCode(), $errorCode, 'Undefined exception: ' . $errorCode . '; Http status: ' . $httpResponse->getStatusCode());

        throw new KeyringWebException($errorCode, $errorMessage, $httpResponse->getStatusCode());
    }
}