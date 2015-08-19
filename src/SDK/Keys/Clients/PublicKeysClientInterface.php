<?php

namespace Virgil\SDK\Keys\Clients;

use Virgil\SDK\Keys\Models\VirgilUserDataCollection;

interface PublicKeysClientInterface {

    public function getKey($publicKeyId);

    public function grabKey($userId, $privateKey = null, $privateKeyPassword = null);

    public function createKey($publicKey, VirgilUserDataCollection $userData, $privateKey , $privateKeyPassword = null);

    public function updateKey($publicKeyId, $publicKey, $privateKey, $privateKeyPassword = null);

    public function deleteKey($publicKey, $privateKey, $privateKeyPassword = null);

}