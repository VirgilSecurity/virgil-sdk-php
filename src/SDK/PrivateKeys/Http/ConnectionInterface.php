<?php

namespace Virgil\SDK\PrivateKeys\Http;

use Virgil\SDK\Common\Http\ConnectionInterface as CommonConnectionInterface;

interface ConnectionInterface extends CommonConnectionInterface {

    public function setAuthCredentials($userName, $userPassword);

}