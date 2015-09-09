<?php

use Virgil\SDK\PrivateKeys\Exception\WebException;

class DeleteContainerTest extends PHPUnit_Framework_TestCase {

    public function test_Should_Delete_Container() {

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

        ContainerHelper::delete(
            Constants::VIRGIL_PUBLIC_KEY_ID,
            Constants::VIRGIL_USER_DATA_VALUE3,
            Constants::VIRGIL_CONTAINER_PASSWORD,
            Constants::VIRGIL_PRIVATE_KEY
        );

        try {
            ContainerHelper::get(
                Constants::VIRGIL_PUBLIC_KEY_ID,
                Constants::VIRGIL_USER_DATA_VALUE3,
                Constants::VIRGIL_CONTAINER_PASSWORD
            );
        } catch (WebException $ex) {
            $this->assertEquals(
                40002,
                $ex->getErrorCode()
            );
        }
    }

} 