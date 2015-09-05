<?php

use Virgil\SDK\Keys\Client as KeysClient;

class UserDataHelper {

    public static function persist($uuid, $confirmationCode) {

        $keysClient = new KeysClient(
            Constants::VIRGIL_APPLICATION_TOKEN,
            array(
                'base_url' => Constants::VIRGIL_KEYS_BASE_URL
            )
        );

        return $keysClient->getUserDataClient()->persistUserData(
            $uuid,
            $confirmationCode
        );
    }
}
