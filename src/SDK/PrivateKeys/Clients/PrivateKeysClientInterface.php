<?php

namespace Virgil\SDK\PrivateKeys\Clients;

interface PrivateKeysClientInterface {

    public function getPrivateKey($publicKeyId);

    public function getAll($accountId);

    public function add($accountId, $publicKeyId, $sign, $privateKey);

    public function remove($publicKeyId, $sign);

}