<?php

namespace Virgil\SDK\Keys;

use Virgil\SDK\Common\Utils\Config,
    Virgil\SDK\Keys\Clients\AccountsClient,
    Virgil\SDK\Keys\Clients\PublicKeysClient,
    Virgil\SDK\Keys\Clients\UserDataClient,
    Virgil\SDK\Keys\Http\Connection;

class Client {

    protected $_accountsClient   = null;
    protected $_publicKeysClient = null;
    protected $_userDataClient   = null;

    protected $_connection       = null;

    public function __construct($appToken, $config = array()) {

        $config = $this->_initConfig(
            $config
        );

        $this->_connection  = new Connection(
            $config->base_url,
            $config->version
        );

        $this->setHeaders(array(
            'X-VIRGIL-APPLICATION-TOKEN' => $appToken
        ));
    }

    /**
     * Setup connection headers
     *
     * @param $headers
     */
    public function setHeaders($headers) {

        $this->_connection->setHeaders(
            $headers
        );
    }

    /**
     * @return AccountsClient
     */
    public function getAccountsClient() {

        if(is_null($this->_accountsClient)) {
            $this->_accountsClient = new AccountsClient(
                $this->_connection
            );
        }

        return $this->_accountsClient;
    }

    /**
     * @return PublicKeysClient
     */
    public function getPublicKeysClient() {

        if(is_null($this->_publicKeysClient)) {
            $this->_publicKeysClient = new PublicKeysClient(
                $this->_connection
            );
        }

        return $this->_publicKeysClient;
    }

    /**
     * @return UserDataClient
     */
    public function getUserDataClient() {

        if(is_null($this->_userDataClient)) {
            $this->_userDataClient = new UserDataClient(
                $this->_connection
            );
        }

        return $this->_userDataClient;
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
