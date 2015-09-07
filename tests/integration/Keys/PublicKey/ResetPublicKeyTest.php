<?php

class ResetPublicKeyTest extends PHPUnit_Framework_TestCase {

    public function test_Should_Reset_PublicKey() {

        PublicKeyHelper::setupPublicKey();

        $publicKey = PublicKeyHelper::create(
            Constants::VIRGIL_PRIVATE_KEY,
            Constants::VIRGIL_PUBLIC_KEY
        );

        UserDataHelper::persist(
            $publicKey->userData->get(0)->id->userDataId,
            MailinatorHelper::fetchMessage(
                Constants::VIRGIL_USER_DATA_VALUE1
            )
        );

        $resetResult = PublicKeyHelper::reset(
            $publicKey->publicKeyId,
            Constants::VIRGIL_PUBLIC_KEY_NEW,
            Constants::VIRGIL_PRIVATE_KEY_NEW
        );

        $publicKey = PublicKeyHelper::persist(
            $publicKey->publicKeyId,
            $resetResult['action_token'],
            array(
                MailinatorHelper::fetchMessage(
                    Constants::VIRGIL_USER_DATA_VALUE1
                )
            )
        );

        $this->assertEquals(
            Constants::VIRGIL_PUBLIC_KEY_NEW,
            $publicKey->publicKey
        );
    }
} 