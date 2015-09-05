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
} 