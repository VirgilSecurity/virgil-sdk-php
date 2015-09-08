<?php

use Virgil\SDK\Keys\Models\UserData,
    Virgil\SDK\Keys\Models\UserDataCollection;

class UserDataHelper extends BaseHelper {

    public static function create($publicKeyId, $dataClass, $dataType, $dataValue) {

        $keysClient = self::getKeysClient();
        $keysClient->setHeaders(array(
            'X-VIRGIL-REQUEST-SIGN-PK-ID' => $publicKeyId
        ));

        $userData = new UserData();
        $userData->class = $dataClass;
        $userData->type  = $dataType;
        $userData->value = $dataValue;

        return $keysClient->getUserDataClient()->createUserData(
            $userData,
            Constants::VIRGIL_PRIVATE_KEY
        );
    }

    public static function delete($publicKeyId, $uuid) {

        $keysClient = self::getKeysClient();
        $keysClient->setHeaders(array(
            'X-VIRGIL-REQUEST-SIGN-PK-ID' => $publicKeyId
        ));

        $keysClient->getUserDataClient()->deleteUserData(
            $uuid,
            Constants::VIRGIL_PRIVATE_KEY
        );
    }

    public static function resend($uuid, $publicKeyId, $privateKey) {

        // Create Keys Service HTTP Client
        $keysClient = self::getKeysClient();
        $keysClient->setHeaders(array(
            'X-VIRGIL-REQUEST-SIGN-PK-ID' => $publicKeyId
        ));

        $keysClient->getUserDataClient()->resendConfirmation(
            $uuid,
            $privateKey
        );
    }

    public static function persist($uuid, $confirmationCode) {

        return self::getKeysClient()->getUserDataClient()->persistUserData(
            $uuid,
            $confirmationCode
        );
    }
}
