<?php

class DeleteUserDataTest extends PHPUnit_Framework_TestCase {

    public function test_Should_Delete_UserData() {

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

        $userData = UserDataHelper::create(
            $publicKey->publicKeyId,
            Constants::VIRGIL_USER_DATA_CLASS,
            Constants::VIRGIL_USER_DATA_TYPE,
            Constants::VIRGIL_USER_DATA_VALUE2
        );

        UserDataHelper::persist(
            $userData->id->userDataId,
            MailinatorHelper::fetchMessage(
                Constants::VIRGIL_USER_DATA_VALUE2
            )
        );

        $publicKey = PublicKeyHelper::grab(
            Constants::VIRGIL_USER_DATA_VALUE2,
            $publicKey->publicKeyId,
            Constants::VIRGIL_PRIVATE_KEY
        );

        $userData = $publicKey->get(0)->userData->get(1);

        $this->assertEquals(
            Constants::VIRGIL_USER_DATA_CLASS,
            $userData->class
        );

        $this->assertEquals(
            Constants::VIRGIL_USER_DATA_TYPE,
            $userData->type
        );

        $this->assertEquals(
            Constants::VIRGIL_USER_DATA_VALUE2,
            $userData->value
        );

        $this->assertTrue(
            $userData->isConfirmed
        );

        UserDataHelper::delete(
            $publicKey->get(0)->publicKeyId,
            $publicKey->get(0)->userData->get(1)->id->userDataId
        );

        $publicKey = PublicKeyHelper::grab(
            Constants::VIRGIL_USER_DATA_VALUE1,
            $publicKey->get(0)->publicKeyId,
            Constants::VIRGIL_PRIVATE_KEY
        );

        $this->assertNull(
            $publicKey->get(0)->userData->get(1)
        );
    }
} 