<?php

class ResetPasswordTest extends PHPUnit_Framework_TestCase {

    public function test_Should_Reset_ContainerPassword() {

        ContainerHelper::setupContainer();

        ContainerHelper::create(
            Constants::VIRGIL_PUBLIC_KEY_ID,
            Constants::VIRGIL_CONTAINER_TYPE_NORMAL,
            Constants::VIRGIL_CONTAINER_PASSWORD,
            Constants::VIRGIL_PRIVATE_KEY
        );

        ContainerHelper::reset(
            Constants::VIRGIL_USER_DATA_VALUE3,
            Constants::VIRGIL_CONTAINER_PASSWORD,
            Constants::VIRGIL_CONTAINER_PASSWORD_NEW
        );

        ContainerHelper::persist(
            Constants::VIRGIL_USER_DATA_VALUE3,
            Constants::VIRGIL_CONTAINER_PASSWORD,
            MailinatorHelper::fetchMessage(
                Constants::VIRGIL_USER_DATA_VALUE3
            )
        );

        $container = ContainerHelper::get(
            Constants::VIRGIL_PUBLIC_KEY_ID,
            Constants::VIRGIL_USER_DATA_VALUE3,
            Constants::VIRGIL_CONTAINER_PASSWORD_NEW
        );

        $this->assertEquals(
            Constants::VIRGIL_CONTAINER_TYPE_NORMAL,
            $container->containerType
        );
    }
}