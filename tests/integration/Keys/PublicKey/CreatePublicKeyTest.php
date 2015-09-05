<?php

use PHPUnit_Framework_TestCase as TestCase,

    Virgil\Crypto\VirgilKeyPair;


class CreatePublicKeyTest extends TestCase {

    public function test_Should_Create_PublicKey() {

        $keyPair = new VirgilKeyPair();

        $publicKey = PublicKeyHelper::create(
            $keyPair->privateKey(), $keyPair->publicKey()
        );

        $this->assertEquals(
            $keyPair->publicKey(),
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
