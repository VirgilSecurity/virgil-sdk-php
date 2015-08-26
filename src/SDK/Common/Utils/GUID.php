<?php

namespace Virgil\SDK\Common\Utils;

class GUID {

    public static function generate() {

        $hash = strtolower(md5(uniqid(rand(), true)));
        $uuid = sprintf(
            '%s-%s-%s-%s-%s',
            substr($hash, 0, 8),
            substr($hash, 8, 4),
            substr($hash, 12, 4),
            substr($hash, 16, 4),
            substr($hash, 20, 12)
        );

        return $uuid;
    }

}