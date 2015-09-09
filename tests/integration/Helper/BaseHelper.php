<?php

use Virgil\SDK\Keys\Client as KeysClient,
    Virgil\SDK\PrivateKeys\Client as PrivateClient;

class BaseHelper {

    public static function getKeysClient() {

        return new KeysClient(
            Constants::VIRGIL_APPLICATION_TOKEN
        );
    }

    public static function getPrivateKeysClient() {

        return new PrivateClient(
            Constants::VIRGIL_APPLICATION_TOKEN
        );
    }
}