<?php

use Virgil\Crypto\VirgilKeyPair;

class GetPublicKeyTest extends PHPUnit_Framework_TestCase {


    public function test_Should_Get_PublicKey() {

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

        $publicKey = PublicKeyHelper::get(
            $publicKey->publicKeyId
        );

        $this->assertEquals(
            Constants::VIRGIL_PUBLIC_KEY,
            $publicKey->publicKey
        );
    }
}
