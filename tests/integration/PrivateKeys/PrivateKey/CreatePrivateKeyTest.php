<?php

class CreatePrivateKeyTest extends PHPUnit_Framework_TestCase {

    public function test_Should_Create_PrivateKey() {

        ContainerHelper::setupContainer();

        ContainerHelper::create(
            Constants::VIRGIL_PUBLIC_KEY_ID,
            Constants::VIRGIL_CONTAINER_TYPE_NORMAL,
            Constants::VIRGIL_CONTAINER_PASSWORD,
            Constants::VIRGIL_PRIVATE_KEY
        );

        PrivateKeyHelper::setupPrivateKey();

        PrivateKeyHelper::create(
            Constants::VIRGIL_PUBLIC_KEY_ID,
            Constants::VIRGIL_USER_DATA_VALUE3,
            Constants::VIRGIL_CONTAINER_PASSWORD,
            Constants::VIRGIL_PRIVATE_KEY
        );

        $privateKey = PrivateKeyHelper::get(
            Constants::VIRGIL_PUBLIC_KEY_ID,
            Constants::VIRGIL_USER_DATA_VALUE3,
            Constants::VIRGIL_CONTAINER_PASSWORD
        );

        $this->assertEquals(
            Constants::VIRGIL_PRIVATE_KEY,
            $privateKey->privateKey
        );
    }
}