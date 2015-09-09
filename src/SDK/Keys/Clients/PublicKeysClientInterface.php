<?php

namespace Virgil\SDK\Keys\Clients;

use Virgil\SDK\Keys\Models\UserDataCollection;

interface PublicKeysClientInterface {

    public function getKey($publicKeyId);

    public function grabKey($userId, $privateKey = null, $privateKeyPassword = null);

    public function createKey($publicKey, UserDataCollection $userData, $privateKey , $privateKeyPassword = null);

    public function updateKey($publicKeyId, $oldPrivateKey, $newPublicKey, $newPrivateKey, $oldPrivateKeyPassword = null, $newPrivateKeyPassword = null);

    public function deleteKey($publicKey, $privateKey, $privateKeyPassword = null);

    public function resetKey($publicKeyId, $publicKey, $privateKey, $privateKeyPassword = null);

    public function persistKey($publicKeyId, $actionToken, $confirmationCodes);

}