<?php

namespace Virgil\SDK\Keys\Clients;

use Virgil\SDK\Keys\Models\VirgilUserData;

interface UserDataClientInterface {

    public function getUserData($uuid);

    public function createUserData(VirgilUserData $virgilUserData, $privateKey, $privateKeyPassword = null);

    public function persistUserData($uuid, $confirmationCode);

    public function deleteUserData($uuid, $privateKey, $privateKeyPassword = null);

    public function resendConfirmation($uuid, $privateKey, $privateKeyPassword = null);

}