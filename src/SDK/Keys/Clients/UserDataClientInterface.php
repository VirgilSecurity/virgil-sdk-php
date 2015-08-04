<?php

namespace Virgil\SDK\Keys\Clients;

use Virgil\SDK\Keys\Models\VirgilUserData;

interface UserDataClientInterface {

    public function getUserData($userDataId);

    public function insertUserData($certificateId, VirgilUserData $virgilUserData);

    public function confirm($userDataId, $confirmationCode);

    public function deleteUserData($userDataId);

}