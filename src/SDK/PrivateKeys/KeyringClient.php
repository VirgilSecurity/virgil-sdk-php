<?php

namespace Virgil\SDK\PrivateKeys;

use Virgil\SDK\PrivateKeys\Clients\PrivateKeysAccountsClient,
    Virgil\SDK\PrivateKeys\Clients\PrivateKeysClient,
    Virgil\SDK\PrivateKeys\Http\Connection,
    Virgil\SDK\Common\Utils\Config;

class KeyringClient {

    protected $_privateKeysClient         = null;
    protected $_privateKeysAccountsClient = null;

    protected $_connection = null;

    public function __construct($username = null, $password = null, $config = array()) {

        $config = $this->_initConfig(
            $config
        );

        $this->_connection = new Connection(
            $config->base_url,
            $config->version,
            array(
                'username' => $username,
                'password' => $password
            )
        );
    }

    public function setCredentials($username, $password) {

        $this->getPrivateKeysClient()->getConnection()->setCredentials(
            $username,
            $password
        );

        $this->getPrivateKeysAccountsClient()->getConnection()->setCredentials(
            $username,
            $password
        );
    }

    /**
     * @return PrivateKeysAccountsClient
     */
    public function getPrivateKeysAccountsClient() {

        if(is_null($this->_privateKeysAccountsClient)) {
            $this->_privateKeysAccountsClient = new PrivateKeysAccountsClient(
                $this->_connection
            );
        }

        return $this->_privateKeysAccountsClient;
    }

    /**
     * @return PrivateKeysClient
     */
    public function getPrivateKeysClient() {

        if(is_null($this->_privateKeysClient)) {
            $this->_privateKeysClient = new PrivateKeysClient(
                $this->_connection
            );
        }

        return $this->_privateKeysClient;
    }

    /**
     * @param $config
     * @return \Virgil\SDK\Common\Utils\Config
     */
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
