<?php

use Virgil\SDK\Common\Http\Error,
    Virgil\SDK\Keys\Exception\WebException;

class DeletePublicKeyTest extends PHPUnit_Framework_TestCase {

    public function test_Should_Delete_PublicKey() {

        PublicKeyHelper::setupPublicKey();

        $publicKey = PublicKeyHelper::create(
            Constants::VIRGIL_PRIVATE_KEY,
            Constants::VIRGIL_PUBLIC_KEY
        );

        UserDataHelper::persist(
            $publicKey->userData->get(0)->id->userDataId,
            MailinatorHelper::fetchMessage()
        );

        $publicKey = PublicKeyHelper::get(
            $publicKey->publicKeyId
        );

        $this->assertEquals(
            Constants::VIRGIL_PUBLIC_KEY,
            $publicKey->publicKey
        );

        PublicKeyHelper::delete(
            $publicKey->publicKeyId,
            Constants::VIRGIL_PRIVATE_KEY
        );

        try {
            PublicKeyHelper::get(
                $publicKey->publicKeyId
            );
        } catch (WebException $ex) {

            $this->assertEquals(
                404,
                $ex->getHttpStatusCode()
            );
        }
    }
} 