<?php

namespace Virgil\SDK\PrivateKeys;

require_once './../../../vendor/autoload.php';

use Virgil\SDK\PrivateKeys\Clients\PrivateKeysAccountsClient;
use Virgil\SDK\PrivateKeys\Clients\PrivateKeysClient;
use Virgil\SDK\PrivateKeys\Exception\KeyringWebException;
use Virgil\SDK\PrivateKeys\Http\Connection;
use Virgil\SDK\Common\Utils\Config;

class KeyringClient {

    protected $_config                    = null;
    protected $_privateKeysClient         = null;
    protected $_privateKeysAccountsClient = null;

    public function __construct($username = null, $password = null) {
        $this->_config = $this->_initConfig();

        $connection = new Connection($this->_config->base_url, $this->_config->api_version, array(
            'username' => $username, 'password' => $password
        ));

        $this->_privateKeysClient         = new PrivateKeysClient($connection);
        $this->_privateKeysAccountsClient = new PrivateKeysAccountsClient($connection);
    }

    public function setCredentials($username, $password) {
        $this->getPrivateKeysClient()->getConnection()->setCredentials($username, $password);
        $this->getPrivateKeysAccountsClient()->getConnection()->setCredentials($username, $password);
    }

    /**
     * @return PrivateKeysAccountsClient
     */
    public function getPrivateKeysAccountsClient() {
        return $this->_privateKeysAccountsClient;
    }

    /**
     * @return PrivateKeysClient
     */
    public function getPrivateKeysClient() {
        return $this->_privateKeysClient;
    }

    /**
     * @return \Virgil\SDK\Common\Utils\Config
     */
    private function _initConfig() {
        return new Config(parse_ini_file(__DIR__ . DIRECTORY_SEPARATOR . 'config.ini'));
    }

}

try {
    $client = (new KeyringClient('v1t40@example.com', 'password'))->getPrivateKeysClient();
    var_dump($client->getPrivateKey('f961a541-0af9-dd2e-a56d-d449006f79b5'));
} catch(KeyringWebException $e) {
    echo $e->getErrorCode();
    echo $e->getMessage();
}
