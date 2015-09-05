<?php

use Virgil\SDK\Keys\Models\UserData,
    Virgil\SDK\Keys\Models\UserDataCollection,
    Virgil\SDK\Keys\Client as KeysClient;

class PublicKeyHelper {


    public static function create($privateKey, $publicKey, $privateKeyPassword = null) {

        // Create Keys Service HTTP Client
        $keysClient = new KeysClient(
            Constants::VIRGIL_APPLICATION_TOKEN,
            array(
                'base_url' => Constants::VIRGIL_KEYS_BASE_URL
            )
        );

        $userData = new UserData();
        $userData->class = Constants::VIRGIL_USER_DATA_CLASS;
        $userData->type  = Constants::VIRGIL_USER_DATA_TYPE;
        $userData->value = Constants::VIRGIL_USER_DATA_VALUE;

        $userDataCollection = new UserDataCollection();
        $userDataCollection->add(
            $userData
        );

        return $keysClient->getPublicKeysClient()->createKey(
            $publicKey,
            $userDataCollection,
            $privateKey,
            $privateKeyPassword
        );
    }

    public static function get($publicKeyId) {

        $keysClient = new KeysClient(
            Constants::VIRGIL_APPLICATION_TOKEN,
            array(
                'base_url' => Constants::VIRGIL_KEYS_BASE_URL
            )
        );

        return $keysClient->getPublicKeysClient()->getKey(
            $publicKeyId
        );
    }
} 
