<?php

namespace Virgil\SDK\Keys\Clients;

use Virgil\SDK\Common\Clients\ApiClient,
    Virgil\SDK\Keys\Models\VirgilUserData;

class UserDataClient extends ApiClient implements UserDataClientInterface {

    public function getUserData($userDataId) {

        $response = $this->get(
            'user-data/'. $userDataId
        );

        return new VirgilUserData(
            $response->getBody()
        );
    }

    public function createUserData($publicKeyId, VirgilUserData $virgilUserData) {

        $response = $this->post(
            'user-data',
            array(
                'public_key_id' => $publicKeyId,
                'class'         => $virgilUserData->class,
                'type'          => $virgilUserData->type,
                'value'         => $virgilUserData->value
            )
        );

        return new VirgilUserData(
            $response->getBody()
        );
    }

    public function persistUserData($uuid, $confirmationCode) {

        $this->post(
            'user-data/' . $uuid . '/persist',
            array(
                'confirmation_code' => $confirmationCode
            )
        );

        return $this;
    }

    public function deleteUserData($userDataId) {}
}