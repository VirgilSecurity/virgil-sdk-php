<?php

class CreateContainerTest extends PHPUnit_Framework_TestCase {

    public function test_Should_Create_ContainerWithTypeNormal() {

        ContainerHelper::setupContainer();

        ContainerHelper::create(
            Constants::VIRGIL_PUBLIC_KEY_ID,
            Constants::VIRGIL_CONTAINER_TYPE_NORMAL,
            Constants::VIRGIL_CONTAINER_PASSWORD,
            Constants::VIRGIL_PRIVATE_KEY
        );

        $container = ContainerHelper::get(
            Constants::VIRGIL_PUBLIC_KEY_ID,
            Constants::VIRGIL_USER_DATA_VALUE3,
            Constants::VIRGIL_CONTAINER_PASSWORD
        );

        $this->assertEquals(
            Constants::VIRGIL_CONTAINER_TYPE_NORMAL,
            $container->containerType
        );
    }

    public function test_Should_Create_ContainerWithTypeEasy() {

        ContainerHelper::setupContainer();

        ContainerHelper::create(
            Constants::VIRGIL_PUBLIC_KEY_ID,
            Constants::VIRGIL_CONTAINER_TYPE_EASY,
            Constants::VIRGIL_CONTAINER_PASSWORD,
            Constants::VIRGIL_PRIVATE_KEY
        );

        $container = ContainerHelper::get(
            Constants::VIRGIL_PUBLIC_KEY_ID,
            Constants::VIRGIL_USER_DATA_VALUE3,
            Constants::VIRGIL_CONTAINER_PASSWORD
        );

        $this->assertEquals(
            Constants::VIRGIL_CONTAINER_TYPE_EASY,
            $container->containerType
        );
    }
}