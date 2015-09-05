<?php

use PHPUnit_Framework_TestCase as TestCase,

    Virgil\Crypto\VirgilKeyPair;

class GetPublicKeyTest extends TestCase {


    public function test_Should_Get_PublicKey() {

        $keyPair = new VirgilKeyPair();

        $keyPairPrivateKey = $keyPair->privateKey();
        $keyPairPublicKey  = $keyPair->publicKey();

        $publicKey = PublicKeyHelper::create(
            $keyPairPrivateKey, $keyPairPublicKey
        );

        sleep(5);

        $mailClient = new MailinatorHelper(
            Constants::VIRGIL_MAILINATOR_TOKEN
        );

        //Get messages in inbox//
        $messages = $mailClient->fetchInbox(
            Constants::VIRGIL_USER_DATA_VALUE
        );
        $message  = array_pop($messages);
        $messageContent = $mailClient->fetchMail(
            $message['id']
        );

        preg_match(
            '/<b style="font-weight: bold;">([0-9a-z]{6})<\/b>/i',
            $messageContent['parts'][0]['body'],
            $matches
        );

        UserDataHelper::persist(
            $publicKey->userData->get(0)->id->userDataId,
            trim($matches[1])
        );

        $publicKey = PublicKeyHelper::get(
            $publicKey->publicKeyId
        );

        $this->assertEquals(
            $keyPairPublicKey,
            $publicKey->publicKey
        );
    }
}
