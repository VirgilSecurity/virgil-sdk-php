<?php

namespace Virgil\SDK\PrivateKeys\Models;

class VirgilPrivateKeysAccountType {

    private static $_userIdTypes = array(
        'Easy',
        'Normal'
    );

    public static function isValidType($type) {
        return in_array($type, self::$_userIdTypes);
    }

}