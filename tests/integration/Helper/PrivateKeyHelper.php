<?php

class PrivateKeyHelper extends BaseHelper {


    public static function setupPrivateKey() {

        try {
            self::delete(
                Constants::VIRGIL_PUBLIC_KEY_ID,
                Constants::VIRGIL_USER_DATA_VALUE3,
                Constants::VIRGIL_CONTAINER_PASSWORD,
                Constants::VIRGIL_PRIVATE_KEY
            );
        } catch(Exception $ex) {}
    }

    public static function get($publicKeyId, $dataValue, $containerPassword) {

        // Create Keys Service HTTP Client
        $privateKeysClient = self::getPrivateKeysClient();
        $privateKeysClient->setAuthCredentials(
            $dataValue,
            $containerPassword
        );

        return $privateKeysClient->getPrivateKeysClient()->getPrivateKey(
            $publicKeyId
        );
    }

    public static function create($publicKeyId, $dataValue, $containerPassword, $privateKey) {

        $privateKeysClient = self::getPrivateKeysClient();
        $privateKeysClient->setAuthCredentials(
            $dataValue,
            $containerPassword
        );

        $privateKeysClient->setHeaders(array(
            'X-VIRGIL-REQUEST-SIGN-PK-ID' => $publicKeyId
        ));

        $privateKeysClient->getPrivateKeysClient()->createPrivateKey(
            $publicKeyId,
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

        $privateKeysClient->getPrivateKeysClient()->deletePrivateKey(
            $privateKey
        );
    }
} 