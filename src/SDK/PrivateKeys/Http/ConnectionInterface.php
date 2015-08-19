<?php

namespace Virgil\SDK\PrivateKeys\Http;

use Virgil\SDK\Common\Http\ConnectionInterface as CommonConnectionInterface;

interface ConnectionInterface extends CommonConnectionInterface {

    /**
     * @return string
     */
    public function getAuthToken();

    public function setCredentials($username, $password);

}