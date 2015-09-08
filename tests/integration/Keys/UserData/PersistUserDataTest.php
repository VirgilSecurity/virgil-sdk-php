<?php

class PersistUserDataTest extends PHPUnit_Framework_TestCase {

    public function test_Should_Persist_UserData() {

        PublicKeyHelper::setupPublicKey();

        $publicKey = PublicKeyHelper::create(
            Constants::VIRGIL_PRIVATE_KEY,
            Constants::VIRGIL_PUBLIC_KEY
        );

        $userData = $publicKey->userData->get(0);
        $this->assertEquals(
            Constants::VIRGIL_USER_DATA_CLASS,
            $userData->class
        );

        $this->assertEquals(
            Constants::VIRGIL_USER_DATA_TYPE,
            $userData->type
        );

        $this->assertEquals(
            Constants::VIRGIL_USER_DATA_VALUE1,
            $userData->value
        );

        $this->assertFalse(
            $userData->isConfirmed
        );

        UserDataHelper::persist(
            $userData->id->userDataId,
            MailinatorHelper::fetchMessage(
                Constants::VIRGIL_USER_DATA_VALUE1
            )
        );

        $publicKey = PublicKeyHelper::grab(
            Constants::VIRGIL_USER_DATA_VALUE1,
            $publicKey->publicKeyId,
            Constants::VIRGIL_PRIVATE_KEY
        );

        $userData = $publicKey->get(0)->userData->get(0);
        $this->assertEquals(
            Constants::VIRGIL_USER_DATA_CLASS,
            $userData->class
        );

        $this->assertEquals(
            Constants::VIRGIL_USER_DATA_TYPE,
            $userData->type
        );

        $this->assertEquals(
            Constants::VIRGIL_USER_DATA_VALUE1,
            $userData->value
        );

        $this->assertTrue(
            $userData->isConfirmed
        );
    }

} 