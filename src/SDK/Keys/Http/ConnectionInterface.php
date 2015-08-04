<?php

namespace Virgil\SDK\Keys\Http;

use Virgil\SDK\Common\Http\ConnectionInterface as CommonConnectionInterface;

interface ConnectionInterface extends CommonConnectionInterface {

    /**
     * @return string
     */
    public function getAppToken();

}