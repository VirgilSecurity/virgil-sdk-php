<?php

namespace Virgil\SDK\PrivateKeys\Clients;

interface PrivateKeysClientInterface {

    public function getPrivateKey($publicKeyId);

    public function createPrivateKey($publicKeyId, $privateKey, $privateKeyPassword = null);

    public function deletePrivateKey($privateKey, $privateKeyPassword = null);

}