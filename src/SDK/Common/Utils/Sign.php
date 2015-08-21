<?php

namespace Virgil\SDK\Common\Utils;

use Virgil\Crypto\VirgilSigner;


class Sign {

    public static function createSign($data, $privateKey, $privateKeyPassword = null) {

        $virgilSigner = new VirgilSigner();
        if(!is_null($privateKeyPassword)) {
            $sign = $virgilSigner->sign(
                $data,
                $privateKey,
                $privateKeyPassword
            );
        } else {
            $sign = $virgilSigner->sign(
                $data,
                $privateKey
            );
        }

        return $sign;
    }

    public static function createRequestSign($connection, $data, $privateKey, $privateKeyPassword = null) {

        $connection->setHeaders(array(
            'X-VIRGIL-REQUEST-SIGN' => base64_encode(
                self::createSign(
                    json_encode(
                        $data
                    ),
                    $privateKey,
                    $privateKeyPassword
                )
            )
        ));
    }
} 