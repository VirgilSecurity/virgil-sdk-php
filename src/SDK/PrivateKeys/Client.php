<?php

namespace Virgil\SDK\PrivateKeys;

use Virgil\SDK\PrivateKeys\Clients\ContainerClient,
    Virgil\SDK\PrivateKeys\Clients\PrivateKeysClient,
    Virgil\SDK\PrivateKeys\Http\Connection,
    Virgil\SDK\Common\Utils\Config;

class Client {

    protected $_privateKeysClient = null;
    protected $_containerClient   = null;

    protected $_connection = null;

    public function __construct($appToken, $config = array()) {

        $config = $this->_initConfig(
            $config
        );

        $this->_connection = new Connection(
            $config->base_url,
            $config->version
        );

        $this->setHeaders(array(
            'X-VIRGIL-APPLICATION-TOKEN' => $appToken
        ));
    }

    public function setAuthCredentials($userName, $userPassword) {

        $this->_connection->setAuthCredentials(
            $userName,
            $userPassword
        );
    }

    public function setHeaders($headers) {

        $this->_connection->setHeaders(
            $headers
        );
    }

    public function getContainerClient() {

        if(is_null($this->_containerClient)) {
            $this->_containerClient = new ContainerClient(
                $this->_connection
            );
        }

        return $this->_containerClient;
    }

    public function getPrivateKeysClient() {

        if(is_null($this->_privateKeysClient)) {
            $this->_privateKeysClient = new PrivateKeysClient(
                $this->_connection
            );
        }

        return $this->_privateKeysClient;
    }

    private function _initConfig($config) {

        return new Config(
            array_merge(
                parse_ini_file(
                    __DIR__ . DIRECTORY_SEPARATOR . 'config.ini'
                ),
                $config
            )
        );
    }
}
