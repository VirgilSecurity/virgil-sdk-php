<?php

namespace Virgil\SDK\Keys\Clients;

use Virgil\SDK\Keys\Models\VirgilUserData;

interface UserDataClientInterface {

    public function getUserData($userDataId);

    public function createUserData($certificateId, VirgilUserData $virgilUserData);

    public function persistUserData($uuid, $confirmationCode);

    public function deleteUserData($userDataId);

}