<?php

class UpdatePublicKeyTest extends PHPUnit_Framework_TestCase {

    public function test_Should_Update_PublicKey() {

        PublicKeyHelper::setupPublicKey();

        $publicKey = PublicKeyHelper::create(
            Constants::VIRGIL_PRIVATE_KEY,
            Constants::VIRGIL_PUBLIC_KEY
        );

        UserDataHelper::persist(
            $publicKey->userData->get(0)->id->userDataId,
            MailinatorHelper::fetchMessage()
        );

        $publicKey = PublicKeyHelper::update(
            $publicKey->publicKeyId,
            Constants::VIRGIL_PRIVATE_KEY,
            Constants::VIRGIL_PUBLIC_KEY_NEW,
            Constants::VIRGIL_PRIVATE_KEY_NEW
        );

        $this->assertEquals(
            Constants::VIRGIL_PUBLIC_KEY_NEW,
            $publicKey->publicKey
        );
    }
} 