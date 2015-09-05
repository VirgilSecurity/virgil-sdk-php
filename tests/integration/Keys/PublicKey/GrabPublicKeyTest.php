<?php

class GrabPublicKeyTest extends PHPUnit_Framework_TestCase {

    public function test_Should_Grab_PublicKey() {

        PublicKeyHelper::setupPublicKey();

        $publicKey = PublicKeyHelper::create(
            Constants::VIRGIL_PRIVATE_KEY,
            Constants::VIRGIL_PUBLIC_KEY
        );

        UserDataHelper::persist(
            $publicKey->userData->get(0)->id->userDataId,
            MailinatorHelper::fetchMessage()
        );

        $publicKey = PublicKeyHelper::grab(
            Constants::VIRGIL_USER_DATA_VALUE
        );

        $this->assertEquals(
            $publicKey->get(0)->publicKey,
            Constants::VIRGIL_PUBLIC_KEY
        );
    }

    public function test_Should_SignedGrab_PublicKey() {

        PublicKeyHelper::setupPublicKey();

        $publicKey = PublicKeyHelper::create(
            Constants::VIRGIL_PRIVATE_KEY,
            Constants::VIRGIL_PUBLIC_KEY
        );

        UserDataHelper::persist(
            $publicKey->userData->get(0)->id->userDataId,
            MailinatorHelper::fetchMessage()
        );

        $publicKey = PublicKeyHelper::grab(
            Constants::VIRGIL_USER_DATA_VALUE,
            $publicKey->publicKeyId,
            Constants::VIRGIL_PRIVATE_KEY
        );

        $this->assertEquals(
            $publicKey->get(0)->publicKey,
            Constants::VIRGIL_PUBLIC_KEY
        );

        $this->assertEquals(
            Constants::VIRGIL_USER_DATA_CLASS,
            $publicKey->get(0)->userData->get(0)->class
        );

        $this->assertEquals(
            Constants::VIRGIL_USER_DATA_TYPE,
            $publicKey->get(0)->userData->get(0)->type
        );

        $this->assertEquals(
            Constants::VIRGIL_USER_DATA_VALUE,
            $publicKey->get(0)->userData->get(0)->value
        );

        $this->assertTrue(
            $publicKey->get(0)->userData->get(0)->isConfirmed
        );
    }
} 