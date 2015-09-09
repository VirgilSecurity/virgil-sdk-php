<?php

use Virgil\SDK\Keys\Models\UserData,
    Virgil\SDK\Keys\Models\UserDataCollection;

class PublicKeyHelper extends BaseHelper {

    public static function setupPublicKey() {

        try {
            $publicKey = self::grab(
                Constants::VIRGIL_USER_DATA_VALUE1
            );

            self::delete(
                $publicKey->get(0)->publicKeyId,
                Constants::VIRGIL_PRIVATE_KEY
            );
        } catch(Exception $ex) {}

        try {
            $publicKey = self::grab(
                Constants::VIRGIL_USER_DATA_VALUE1
            );

            self::delete(
                $publicKey->get(0)->publicKeyId,
                Constants::VIRGIL_PRIVATE_KEY_NEW
            );
        } catch(Exception $ex) {}

    }

    public static function create($privateKey, $publicKey, $privateKeyPassword = null) {

        $userData = new UserData();
        $userData->class = Constants::VIRGIL_USER_DATA_CLASS;
        $userData->type  = Constants::VIRGIL_USER_DATA_TYPE;
        $userData->value = Constants::VIRGIL_USER_DATA_VALUE1;

        $userDataCollection = new UserDataCollection();
        $userDataCollection->add(
            $userData
        );

        $keysClient = self::getKeysClient();
        return $keysClient->getPublicKeysClient()->createKey(
            $publicKey,
            $userDataCollection,
            $privateKey,
            $privateKeyPassword
        );
    }

    public static function get($publicKeyId) {

        $keysClient = self::getKeysClient();
        return $keysClient->getPublicKeysClient()->getKey(
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

    public static function update($publicKeyId, $oldPrivateKey, $newPublicKey, $newPrivateKey, $oldPrivateKeyPassword = null, $newPrivateKeyPassword = null) {

        $keysClient = self::getKeysClient();
        $keysClient->setHeaders(array(
            'X-VIRGIL-REQUEST-SIGN-PK-ID' => $publicKeyId
        ));

        return $keysClient->getPublicKeysClient()->updateKey(
            $publicKeyId,
            $oldPrivateKey,
            $newPublicKey,
            $newPrivateKey,
            $oldPrivateKeyPassword,
            $newPrivateKeyPassword
        );
    }

    public static function reset($publicKeyId, $newPublicKey, $newPrivateKey, $newPrivateKeyPassword = null) {

        $keysClient = self::getKeysClient();
        return $keysClient->getPublicKeysClient()->resetKey(
            $publicKeyId,
            $newPublicKey,
            $newPrivateKey,
            $newPrivateKeyPassword
        );
    }

    public static function persist($publicKeyId, $actionToken, $confirmationCodes) {

        $keysClient = self::getKeysClient();
        return $keysClient->getPublicKeysClient()->persistKey(
            $publicKeyId,
            $actionToken,
            $confirmationCodes
        );
    }
} 
