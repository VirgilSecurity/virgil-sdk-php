<?php

use Virgil\SDK\Keys\Models\UserData,
    Virgil\SDK\Keys\Models\UserDataCollection;

class PublicKeyHelper extends BaseHelper {

    public static function create($privateKey, $publicKey, $privateKeyPassword = null) {

        $userData = new UserData();
        $userData->class = Constants::VIRGIL_USER_DATA_CLASS;
        $userData->type  = Constants::VIRGIL_USER_DATA_TYPE;
        $userData->value = Constants::VIRGIL_USER_DATA_VALUE;

        $userDataCollection = new UserDataCollection();
        $userDataCollection->add(
            $userData
        );

        return self::getKeysClient()->getPublicKeysClient()->createKey(
            $publicKey,
            $userDataCollection,
            $privateKey,
            $privateKeyPassword
        );
    }

    public static function get($publicKeyId) {

        return self::getKeysClient()->getPublicKeysClient()->getKey(
            $publicKeyId
        );
    }

    public static function delete($publicKeyId, $privateKey, $privateKeyPassword = null) {

        $keysClient = self::getKeysClient();
        $keysClient->setHeaders(array(
            'X-VIRGIL-REQUEST-SIGN-PK-ID' => $publicKeyId
        ));

        return $keysClient->getPublicKeysClient()->deleteKey(
            $publicKeyId,
            $privateKey,
            $privateKeyPassword
        );
    }

    public static function grab($userDataValue, $publicKeyId = null, $privateKey = null, $privateKeyPassword = null) {

        $keysClient = self::getKeysClient();
        if(!is_null($publicKeyId)) {
            $keysClient->setHeaders(array(
                'X-VIRGIL-REQUEST-SIGN-PK-ID' => $publicKeyId
            ));
        }

        return $keysClient->getPublicKeysClient()->grabKey(
            $userDataValue,
            $privateKey,
            $privateKeyPassword
        );
    }

    public static function update($publicKeyId, $oldPublicKey, $oldPrivateKey, $newPublicKey, $newPrivateKey, $oldPrivateKeyPassword = null, $newPrivateKeyPassword = null) {

        $keysClient = self::getKeysClient();
        $keysClient->setHeaders(array(
            'X-VIRGIL-REQUEST-SIGN-PK-ID' => $publicKeyId
        ));

        return $keysClient->getPublicKeysClient()->updateKey(
            $publicKeyId,
            $oldPublicKey,
            $oldPrivateKey,
            $newPublicKey,
            $newPrivateKey,
            $oldPrivateKeyPassword,
            $newPrivateKeyPassword
        );
    }
} 
