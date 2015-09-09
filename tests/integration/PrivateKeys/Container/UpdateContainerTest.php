<?php

class UpdateContainerTest extends PHPUnit_Framework_TestCase {

    public function test_Should_Update_ContainerWithNormalType() {

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

        ContainerHelper::update(
            Constants::VIRGIL_PUBLIC_KEY_ID,
            Constants::VIRGIL_USER_DATA_VALUE3,
            Constants::VIRGIL_CONTAINER_PASSWORD,
            Constants::VIRGIL_PRIVATE_KEY,
            Constants::VIRGIL_CONTAINER_TYPE_EASY,
            Constants::VIRGIL_CONTAINER_PASSWORD
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

    public function test_Should_Update_ContainerWithEasyType() {

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

        ContainerHelper::update(
            Constants::VIRGIL_PUBLIC_KEY_ID,
            Constants::VIRGIL_USER_DATA_VALUE3,
            Constants::VIRGIL_CONTAINER_PASSWORD,
            Constants::VIRGIL_PRIVATE_KEY,
            Constants::VIRGIL_CONTAINER_TYPE_NORMAL,
            Constants::VIRGIL_CONTAINER_PASSWORD
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
}