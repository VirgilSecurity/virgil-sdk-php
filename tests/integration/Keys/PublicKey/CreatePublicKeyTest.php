<?php

use PHPUnit_Framework_TestCase as TestCase,

    Virgil\Crypto\VirgilKeyPair;


class CreatePublicKeyTest extends TestCase {

    public function test_Should_Create_PublicKey() {

        try {
            $publicKey = PublicKeyHelper::grab(
                Constants::VIRGIL_USER_DATA_VALUE
            );

            PublicKeyHelper::delete(
                $publicKey->get(0)->publicKeyId,
                Constants::VIRGIL_PRIVATE_KEY
            );
        } catch(Exception $ex) {}

        $publicKey = PublicKeyHelper::create(
            Constants::VIRGIL_PRIVATE_KEY, Constants::VIRGIL_PUBLIC_KEY
        );

        $this->assertEquals(
            Constants::VIRGIL_PUBLIC_KEY,
            $publicKey->publicKey
        );

        $this->assertEquals(
            Constants::VIRGIL_USER_DATA_CLASS,
            $publicKey->userData->get(0)->class
        );

        $this->assertEquals(
            Constants::VIRGIL_USER_DATA_TYPE,
            $publicKey->userData->get(0)->type
        );

        $this->assertEquals(
            Constants::VIRGIL_USER_DATA_VALUE,
            $publicKey->userData->get(0)->value
        );

        $this->assertFalse(
            $publicKey->userData->get(0)->isConfirmed
        );
    }
}
