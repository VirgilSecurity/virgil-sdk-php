<?php

namespace Virgil\SDK\PrivateKeys\Clients;

use Virgil\SDK\PrivateKeys\Models\VirgilUserData;

interface ContainerClientInterface {

    public function getContainer($uuid);

    public function createContainer($containerType, $containerPassword, $privateKey, $privateKeyPassword = null);

    public function updateContainer($containerType = null, $containerPassword = null, $privateKey, $privateKeyPassword = null);

    public function deleteContainer($privateKey, $privateKeyPassword = null);

    public function resetPassword(VirgilUserData $userData, $password);

    public function persistContainer($token);
}