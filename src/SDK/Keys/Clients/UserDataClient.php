<?php

namespace Virgil\SDK\Keys\Clients;

use Virgil\SDK\Common\Clients\ApiClient,
    Virgil\SDK\Keys\Models\VirgilUserData,
    Virgil\SDK\Common\Utils\GUID,
    Virgil\SDK\Common\Utils\Sign;

class UserDataClient extends ApiClient implements UserDataClientInterface {

    public function getUserData($uuid) {

        $response = $this->get(
            'user-data/'. $uuid
        );

        return new VirgilUserData(
            $response->getBody()
        );
    }

    public function createUserData(VirgilUserData $virgilUserData, $privateKey, $privateKeyPassword = null) {

        $request = array(
            'class'             => $virgilUserData->class,
            'type'              => $virgilUserData->type,
            'value'             => $virgilUserData->value,
            'request_sign_uuid' => GUID::generate()
        );

        Sign::createRequestSign(
            $this->getConnection(),
            $request,
            $privateKey,
            $privateKeyPassword
        );

        return new VirgilUserData(
            $this->post(
                'user-data',
                $request
            )->getBody()
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

    public function deleteUserData($uuid, $privateKey, $privateKeyPassword = null) {

        $request = array(
            'request_sign_uuid' => GUID::generate()
        );

        Sign::createRequestSign(
            $this->getConnection(),
            $request,
            $privateKey,
            $privateKeyPassword
        );

        $this->delete(
            'user-data/' . $uuid,
            $request
        );

        return $this;
    }

    public function resendConfirmation($uuid, $privateKey, $privateKeyPassword = null) {

        $request = array(
            'request_sign_uuid' => GUID::generate()
        );

        Sign::createRequestSign(
            $this->getConnection(),
            $request,
            $privateKey,
            $privateKeyPassword
        );

        $this->post(
            'user-data/' . $uuid . '/actions/resend-confirmation',
            $request
        );

        return $this;
    }
}