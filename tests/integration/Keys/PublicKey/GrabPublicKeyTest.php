<?php

class GrabPublicKeyTest extends PHPUnit_Framework_TestCase {

    public function test_Should_Grab_PublicKey() {

        PublicKeyHelper::setupPublicKey();

        $publicKey = PublicKeyHelper::create(
            Constants::VIRGIL_PRIVATE_KEY,
            Constants::VIRGIL_PUBLIC_KEY
        );

        sleep(5);

        $mailClient = new MailinatorHelper(
            Constants::VIRGIL_MAILINATOR_TOKEN
        );

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

        $publicKey = PublicKeyHelper::grab(
            Constants::VIRGIL_USER_DATA_VALUE
        );

        $this->assertEquals(
            $publicKey->get(0)->publicKey,
            Constants::VIRGIL_PUBLIC_KEY
        );
    }

    public function test_Should_SignedGrab_PublicKey() {

        PublicKeyHelper::setupPublicKey();

        $publicKey = PublicKeyHelper::create(
            Constants::VIRGIL_PRIVATE_KEY,
            Constants::VIRGIL_PUBLIC_KEY
        );

        sleep(5);

        $mailClient = new MailinatorHelper(
            Constants::VIRGIL_MAILINATOR_TOKEN
        );

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

        $publicKey = PublicKeyHelper::grab(
            Constants::VIRGIL_USER_DATA_VALUE,
            $publicKey->publicKeyId,
            Constants::VIRGIL_PRIVATE_KEY
        );

        $this->assertEquals(
            $publicKey->get(0)->publicKey,
            Constants::VIRGIL_PUBLIC_KEY
        );

        $this->assertEquals(
            Constants::VIRGIL_USER_DATA_CLASS,
            $publicKey->get(0)->userData->get(0)->class
        );

        $this->assertEquals(
            Constants::VIRGIL_USER_DATA_TYPE,
            $publicKey->get(0)->userData->get(0)->type
        );

        $this->assertEquals(
            Constants::VIRGIL_USER_DATA_VALUE,
            $publicKey->get(0)->userData->get(0)->value
        );

        $this->assertTrue(
            $publicKey->get(0)->userData->get(0)->isConfirmed
        );
    }
} 