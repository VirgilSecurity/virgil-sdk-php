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