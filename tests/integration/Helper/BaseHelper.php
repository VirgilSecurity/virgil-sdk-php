<?php

use Virgil\SDK\Keys\Client as KeysClient;

class BaseHelper {

    public static function getKeysClient() {

        return new KeysClient(
            Constants::VIRGIL_APPLICATION_TOKEN,
            array(
                'base_url' => Constants::VIRGIL_KEYS_BASE_URL
            )
        );
    }

    public static function setupPublicKey() {

        try {
            $publicKey = PublicKeyHelper::grab(
                Constants::VIRGIL_USER_DATA_VALUE
            );

            PublicKeyHelper::delete(
                $publicKey->get(0)->publicKeyId,
                Constants::VIRGIL_PRIVATE_KEY
            );
        } catch(Exception $ex) {}
    }
} 