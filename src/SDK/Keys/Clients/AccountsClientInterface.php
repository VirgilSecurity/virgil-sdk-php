<?php

namespace Virgil\SDK\Keys\Clients;

interface AccountsClientInterface {

    public function register($userDataType, $userId, $publicKey);

}