<?php

class UserDataHelper extends BaseHelper {

    public static function persist($uuid, $confirmationCode) {

        return self::getKeysClient()->getUserDataClient()->persistUserData(
            $uuid,
            $confirmationCode
        );
    }
}
