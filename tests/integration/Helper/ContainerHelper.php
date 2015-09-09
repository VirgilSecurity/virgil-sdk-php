<?php

use Virgil\SDK\PrivateKeys\Models\UserData;


class ContainerHelper extends BaseHelper {

    public static function setupContainer() {

        try {
            self::delete(
                Constants::VIRGIL_PUBLIC_KEY_ID,
                Constants::VIRGIL_USER_DATA_VALUE3,
                Constants::VIRGIL_CONTAINER_PASSWORD,
                Constants::VIRGIL_PRIVATE_KEY
            );
        } catch(Exception $ex) {}

        try {
            self::delete(
                Constants::VIRGIL_PUBLIC_KEY_ID,
                Constants::VIRGIL_USER_DATA_VALUE3,
                Constants::VIRGIL_CONTAINER_PASSWORD_NEW,
                Constants::VIRGIL_PRIVATE_KEY
            );
        } catch(Exception $ex) {}
    }

    public static function get($publicKeyId, $dataValue, $containerPassword) {

        $privateKeysClient = self::getPrivateKeysClient();
        $privateKeysClient->setAuthCredentials(
            $dataValue,
            $containerPassword
        );

        return $privateKeysClient->getContainerClient()->getContainer(
            $publicKeyId
        );
    }

    public static function create($publicKeyId, $containerType, $containerPassword, $privateKey) {

        $privateKeysClient = self::getPrivateKeysClient();
        $privateKeysClient->setHeaders(array(
            'X-VIRGIL-REQUEST-SIGN-PK-ID' => $publicKeyId
        ));

        $privateKeysClient->getContainerClient()->createContainer(
            $containerType,
            $containerPassword,
            $privateKey
        );
    }

    public static function update($publicKeyId, $dataValue, $containerPassword, $privateKey, $newContainerType = null, $newContainerPassword = null) {

        $privateKeysClient = self::getPrivateKeysClient();;
        $privateKeysClient->setAuthCredentials(
            $dataValue,
            $containerPassword
        );

        $privateKeysClient->setHeaders(array(
            'X-VIRGIL-REQUEST-SIGN-PK-ID' => $publicKeyId
        ));

        $privateKeysClient->getContainerClient()->updateContainer(
            $newContainerType,
            $newContainerPassword,
            $privateKey
        );
    }

    public static function delete($publicKeyId, $dataValue, $containerPassword, $privateKey) {

        $privateKeysClient = self::getPrivateKeysClient();
        $privateKeysClient->setAuthCredentials(
            $dataValue,
            $containerPassword
        );

        $privateKeysClient->setHeaders(array(
            'X-VIRGIL-REQUEST-SIGN-PK-ID' => $publicKeyId
        ));

        $privateKeysClient->getContainerClient()->deleteContainer(
            $privateKey
        );
    }

    public static function reset($dataValue, $oldContainerPassword, $newContainerPassword) {

        $privateKeysClient = self::getPrivateKeysClient();
        $privateKeysClient->setAuthCredentials(
            $dataValue,
            $oldContainerPassword
        );

        $userData = new UserData();
        $userData->class = Constants::VIRGIL_USER_DATA_CLASS;
        $userData->type  = Constants::VIRGIL_USER_DATA_TYPE;
        $userData->value = $dataValue;

        $privateKeysClient->getContainerClient()->resetPassword(
            $userData,
            $newContainerPassword
        );
    }

    public static function persist($dataValue, $oldContainerPassword, $confirmationToken) {

        $privateKeysClient = self::getPrivateKeysClient();
        $privateKeysClient->setAuthCredentials(
            $dataValue,
            $oldContainerPassword
        );

        $privateKeysClient->getContainerClient()->persistContainer(
            $confirmationToken
        );
    }
} 