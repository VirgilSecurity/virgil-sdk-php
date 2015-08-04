<?php

namespace Virgil\SDK\Keys\Clients;

use Virgil\SDK\Keys\Models\VirgilUserDataCollection;

interface PublicKeysClientInterface {

    public function getKey($publicKeyId);

    public function searchKey($userId, $userDataType);

    public function addKey($accountId, $publicKey, VirgilUserDataCollection $userData);

}