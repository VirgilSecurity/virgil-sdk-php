<?php
namespace Virgil\Sdk\Tests\Unit\Cryptography\Core;


use Virgil\Sdk\Tests\BaseTestCase;

use Virgil\Sdk\Cryptography\Constants\KeyPairTypes;

use Virgil\Sdk\Cryptography\Core\VirgilCryptoService;
use Virgil\Sdk\Cryptography\Core\VirgilKeyPair;

class VirgilCryptoServiceTest extends BaseTestCase
{
    /** @var VirgilCryptoService */
    private $cryptoService;


    public function setUp()
    {
        $this->cryptoService = new VirgilCryptoService();
    }


    /**
     * @test
     *
     * @return array
     */
    public function generateKeyPair__withOneOfKeyPairTypes__returnsValidKeyPair()
    {
        $keyPairType = KeyPairTypes::FAST_EC_ED25519;


        $aliceKeyPair = $this->cryptoService->generateKeyPair($keyPairType);
        $bobKeyPair = $this->cryptoService->generateKeyPair($keyPairType);


        $this->assertInstanceOf(VirgilKeyPair::class, $aliceKeyPair);
        $this->assertInstanceOf(VirgilKeyPair::class, $bobKeyPair);

        return [$aliceKeyPair, $bobKeyPair];
    }


    /**
     * @expectedException \Virgil\Sdk\Cryptography\Core\Exceptions\KeyPairGenerationException
     *
     * @test
     */
    public function generateKeyPair__withWrongKeyPairType__throwsException()
    {
        $invalidKeyPairType = 21;


        $this->cryptoService->generateKeyPair($invalidKeyPairType);


        //expected exception
    }


    /**
     * @depends  generateKeyPair__withOneOfKeyPairTypes__returnsValidKeyPair
     *
     * @param array $keyPairs
     *
     * @test
     */
    public function isKeyPair__forSameKeyPair__returnsTrue(
        array $keyPairs
    ) {
        list($aliceKeyPair, $bobKeyPair) = $keyPairs;


        $isSameKeyPair = $this->cryptoService->isKeyPair($aliceKeyPair->getPublicKey(), $aliceKeyPair->getPrivateKey());


        $this->assertTrue($isSameKeyPair);
    }


    /**
     * @depends  generateKeyPair__withOneOfKeyPairTypes__returnsValidKeyPair
     *
     * @param array $keyPairs
     *
     * @test
     */
    public function isKeyPair__forDifferentKeyPairs__returnsFalse(
        array $keyPairs
    ) {
        list($aliceKeyPair, $bobKeyPair) = $keyPairs;


        $isSameKeyPair = $this->cryptoService->isKeyPair($bobKeyPair->getPublicKey(), $aliceKeyPair->getPrivateKey());


        $this->assertFalse($isSameKeyPair);
    }


    /**
     * @expectedException \Virgil\Sdk\Cryptography\Core\Exceptions\PublicKeyToDerConvertingException
     *
     * @test
     */
    public function publicKeyToDer__withInvalidPublicKey__throwsException()
    {
        $invalidPublicKey = 'wrong key';


        $this->cryptoService->publicKeyToDer($invalidPublicKey);


        //expected exception
    }


    /**
     * @expectedException \Virgil\Sdk\Cryptography\Core\Exceptions\PrivateKeyToDerConvertingException
     *
     * @test
     */
    public function privateKeyToDer__withInvalidPublicKey__throwsException()
    {
        $invalidPrivateKey = 'wrong key';


        $this->cryptoService->privateKeyToDer($invalidPrivateKey);


        //expected exception
    }


    /**
     * @expectedException \Virgil\Sdk\Cryptography\Core\Exceptions\PublicKeyHashComputationException
     *
     * @test
     */
    public function computeHash__withInvalidArguments__throwsException()
    {
        $invalidPublicKey = 'wrong key';
        $invalidHashAlgorithm = 'wrong algorithm';


        $this->cryptoService->computeHash($invalidPublicKey, $invalidHashAlgorithm);


        //expected exception
    }


    /**
     * @depends generateKeyPair__withOneOfKeyPairTypes__returnsValidKeyPair
     *
     * @param array $keyPairs
     *
     * @test
     */
    public function extractPublicKey__fromPrivateKey__returnsPublicKey(
        array $keyPairs
    ) {
        list($aliceKeyPair, $bobKeyPair) = $keyPairs;

        $extractPassword = '';


        $extractedAlicePublicKey = $this->cryptoService->extractPublicKey(
            $aliceKeyPair->getPrivateKey(),
            $extractPassword
        );


        $this->assertEquals($aliceKeyPair->getPublicKey(), $extractedAlicePublicKey);
    }


    /**
     * @expectedException \Virgil\Sdk\Cryptography\Core\Exceptions\PublicKeyExtractionException
     *
     * @test
     */
    public function extractPublicKey__fromInvalidPrivateKey__throwsException()
    {
        $invalidPrivateKey = 'wrong private key';
        $encryptPassword = '';


        $this->cryptoService->extractPublicKey($invalidPrivateKey, $encryptPassword);


        //expected exception
    }


    /**
     * @depends generateKeyPair__withOneOfKeyPairTypes__returnsValidKeyPair
     *
     * @param VirgilKeyPair[] $keyPairs
     *
     * @test
     *
     * @return array
     */
    public function encryptPrivateKey__withPrivateKeyAndPassword__returnsEncryptedPrivateKey(
        array $keyPairs
    ) {
        list($aliceKeyPair, $bobKeyPair) = $keyPairs;

        $encryptPassword = 'qwerty';
        $alicePrivateKey = $aliceKeyPair->getPrivateKey();


        $encryptedPrivateKey = $this->cryptoService->encryptPrivateKey($alicePrivateKey, $encryptPassword);


        $this->assertNotEquals($alicePrivateKey, $encryptedPrivateKey);


        return [$alicePrivateKey, $encryptedPrivateKey, $encryptPassword];
    }


    /**
     * @depends encryptPrivateKey__withPrivateKeyAndPassword__returnsEncryptedPrivateKey
     *
     * @param $encryptPrivateKeyWithPrivateKeyAndPasswordData
     *
     * @test
     */
    public function decryptPrivateKey__withEncryptedPrivateKeyAndPassword__returnsOriginalPrivateKey(
        $encryptPrivateKeyWithPrivateKeyAndPasswordData
    ) {
        list($alicePrivateKey, $encryptedPrivateKey, $encryptPassword) = $encryptPrivateKeyWithPrivateKeyAndPasswordData;


        $decryptedPrivateKey = $this->cryptoService->decryptPrivateKey($encryptedPrivateKey, $encryptPassword);


        $this->assertEquals($alicePrivateKey, $decryptedPrivateKey);
    }


    /**
     * @expectedException \Virgil\Sdk\Cryptography\Core\Exceptions\PrivateKeyEncryptionException
     *
     * @test
     */
    public function encryptPrivateKey__withInvalidPrivateKeyAndPassword__throwsException()
    {
        $invalidPrivateKey = 'wrong private key';
        $encryptPassword = '';


        $this->cryptoService->encryptPrivateKey($invalidPrivateKey, $encryptPassword);


        //expected exception
    }


    /**
     * @expectedException \Virgil\Sdk\Cryptography\Core\Exceptions\PrivateKeyDecryptionException
     *
     * @test
     */
    public function decryptPrivateKey__withInvalidEncryptedPrivateKeyAndPassword__throwsException()
    {
        $invalidEncryptedPrivateKey = 'wrong private key';
        $encryptPassword = '';


        $this->cryptoService->decryptPrivateKey($invalidEncryptedPrivateKey, $encryptPassword);


        //expected exception
    }


    /**
     * @expectedException \Virgil\Sdk\Cryptography\Core\Exceptions\ContentVerificationException
     *
     * @test
     */
    public function verify__withInvalidSignatureFormat__throwsException()
    {
        $content = 'data';
        $invalidSignatureFormat = 'wrong signature';

        $aliceKeyPair = $this->cryptoService->generateKeyPair(KeyPairTypes::FAST_EC_ED25519);


        $this->cryptoService->verify($content, $invalidSignatureFormat, $aliceKeyPair->getPublicKey());


        //expected exception
    }


    /**
     * @expectedException \Virgil\Sdk\Cryptography\Core\Exceptions\ContentVerificationException
     *
     * @test
     */
    public function verifyStream__withInvalidSignatureFormat__throwsException()
    {
        $sourceStream = fopen('php://memory', 'r+');
        $content = 'data';
        fwrite($sourceStream, $content);
        $invalidSignatureFormat = 'wrong signature';

        $aliceKeyPair = $this->cryptoService->generateKeyPair(KeyPairTypes::FAST_EC_ED25519);


        $this->cryptoService->verifyStream($sourceStream, $invalidSignatureFormat, $aliceKeyPair->getPublicKey());


        //expected exception
    }


    /**
     * @expectedException \Virgil\Sdk\Cryptography\Core\Exceptions\ContentSigningException
     *
     * @test
     */
    public function sign__withInvalidPrivateKey__throwsException()
    {
        $invalidPrivateKey = 'wrong private key';
        $content = 'data';


        $this->cryptoService->sign($content, $invalidPrivateKey);


        //expected exception
    }


    /**
     * @depends generateKeyPair__withOneOfKeyPairTypes__returnsValidKeyPair
     *
     * @param VirgilKeyPair[] $keyPairs
     *
     * @test
     *
     * @return array
     */
    public function sign__withPrivateKey__returnsSignature(
        array $keyPairs
    ) {
        list($aliceKeyPair, $bobKeyPair) = $keyPairs;

        $content = 'data';
        $alicePrivateKey = $aliceKeyPair->getPrivateKey();
        $alicePublicKey = $aliceKeyPair->getPublicKey();
        $bobPublicKey = $bobKeyPair->getPublicKey();


        $contentSignature = $this->cryptoService->sign($content, $alicePrivateKey);


        $this->assertNotEmpty($contentSignature);


        return [$content, $contentSignature, $alicePublicKey, $bobPublicKey];
    }


    /**
     * @depends sign__withPrivateKey__returnsSignature
     *
     * @param array $signWithPrivateKeyData
     *
     * @test
     */
    public function verify__withPublicKeys__returnsValidationResult(
        array $signWithPrivateKeyData
    ) {
        list($content, $contentSignature, $signerPublicKey, $invalidPublicKey) = $signWithPrivateKeyData;


        $isValid = $this->cryptoService->verify($content, $contentSignature, $signerPublicKey);
        $isInvalid = $this->cryptoService->verify($content, $contentSignature, $invalidPublicKey);


        $this->assertTrue($isValid);
        $this->assertFalse($isInvalid);
    }


    /**
     * @expectedException \Virgil\Sdk\Cryptography\Core\Exceptions\ContentSigningException
     *
     * @test
     */
    public function signStream__withInvalidPrivateKey__throwsException()
    {
        $invalidPrivateKey = 'wrong private key';
        $streamSource = fopen('php://memory', 'r+');


        $this->cryptoService->signStream($streamSource, $invalidPrivateKey);


        //expected exception
    }


    /**
     * @depends generateKeyPair__withOneOfKeyPairTypes__returnsValidKeyPair
     *
     * @param VirgilKeyPair[] $keyPairs
     *
     * @test
     *
     * @return array
     */
    public function signStream__withPrivateKey__returnsSignature(
        array $keyPairs
    ) {
        list($aliceKeyPair, $bobKeyPair) = $keyPairs;

        $sourceStream = fopen('php://memory', 'r+');
        $data = 'data';
        fwrite($sourceStream, $data);

        $alicePrivateKey = $aliceKeyPair->getPrivateKey();
        $alicePublicKey = $aliceKeyPair->getPublicKey();
        $bobPublicKey = $bobKeyPair->getPublicKey();


        $contentSignature = $this->cryptoService->signStream($sourceStream, $alicePrivateKey);


        $this->assertNotEmpty($contentSignature);


        return [$sourceStream, $contentSignature, $alicePublicKey, $bobPublicKey];
    }


    /**
     * @depends signStream__withPrivateKey__returnsSignature
     *
     * @param array $signStreamWithPrivateKeyData
     *
     * @test
     */
    public function verifyStream__withPublicKeys__returnsValidationResult(
        array $signStreamWithPrivateKeyData
    ) {
        list($sourceStream, $contentSignature, $signerPublicKey, $invalidPublicKey) = $signStreamWithPrivateKeyData;


        $isValid = $this->cryptoService->verifyStream($sourceStream, $contentSignature, $signerPublicKey);
        $isInvalid = $this->cryptoService->verifyStream($sourceStream, $contentSignature, $invalidPublicKey);


        $this->assertTrue($isValid);
        $this->assertFalse($isInvalid);
    }


    /**
     * @test
     */
    public function encrypt__withoutRecipients__returnsEncryptedData()
    {
        $content = 'data';
        $receiverId = 'SALGH&';
        $cipher = $this->cryptoService->createCipher();


        $encryptedContent = $cipher->encrypt($cipher->createInputOutput($content));


        $this->assertNotEquals($content, $encryptedContent);


        return [$encryptedContent, $cipher, $receiverId];
    }


    /**
     * @expectedException \Virgil\Sdk\Cryptography\Core\Exceptions\CipherException
     *
     * @depends generateKeyPair__withOneOfKeyPairTypes__returnsValidKeyPair
     * @depends encrypt__withoutRecipients__returnsEncryptedData
     *
     * @param array $keyPairs
     * @param array $encryptWithoutRecipientsData
     *
     * @test
     */
    public function decryptWithKey__withoutRecipients__throwsException(
        array $keyPairs,
        array $encryptWithoutRecipientsData
    ) {
        list($aliceKeyPair, $bobKeyPair) = $keyPairs;
        list($encryptedContent, $cipher, $receiverId) = $encryptWithoutRecipientsData;


        $cipher->decryptWithKey(
            $cipher->createInputOutput($encryptedContent),
            $receiverId,
            $aliceKeyPair->getPrivateKey()
        );


        //expected exception
    }


    /**
     * @depends generateKeyPair__withOneOfKeyPairTypes__returnsValidKeyPair
     *
     * @param VirgilKeyPair[] $keyPairs
     *
     * @test
     *
     * @return array
     */
    public function encrypt__withRecipients__returnsEncryptedData(
        array $keyPairs
    ) {
        list($aliceKeyPair, $bobKeyPair) = $keyPairs;

        $content = 'data';
        $aliceReceiverId = 'SALGH&';
        $bobReceiverId = 'ZLKG&';

        $cipher = $this->cryptoService->createCipher();

        $cipher->addKeyRecipient($aliceReceiverId, $aliceKeyPair->getPublicKey());
        $cipher->addKeyRecipient($bobReceiverId, $bobKeyPair->getPublicKey());


        $encryptedContent = $cipher->encrypt($cipher->createInputOutput($content));


        $this->assertNotEquals($content, $encryptedContent);


        return [
            $content,
            $encryptedContent,
            $cipher,
            [
                $aliceReceiverId,
                $aliceKeyPair->getPrivateKey(),
            ],
            [
                $bobReceiverId,
                $bobKeyPair->getPrivateKey(),
            ],
        ];
    }


    /**
     * @depends encrypt__withRecipients__returnsEncryptedData
     *
     * @param array $encryptWithRecipientsData
     *
     * @test
     */
    public function decryptWithKey__withRecipients__returnsOriginalData(
        array $encryptWithRecipientsData
    ) {
        list($originalContent, $encryptedContent, $cipher, $alicePrivateKeyWithId, $bobPrivateKeyWithId) = $encryptWithRecipientsData;
        list($aliceReceiverId, $alicePrivateKey) = $alicePrivateKeyWithId;
        list($bobReceiverId, $bobPrivateKey) = $bobPrivateKeyWithId;

        $cipherInputOutput = $cipher->createInputOutput($encryptedContent);


        $decryptedContentByAlice = $cipher->decryptWithKey(
            $cipherInputOutput,
            $aliceReceiverId,
            $alicePrivateKey
        );

        $decryptedContentByBob = $cipher->decryptWithKey(
            $cipherInputOutput,
            $bobReceiverId,
            $bobPrivateKey
        );


        $this->assertEquals($originalContent, $decryptedContentByAlice);
        $this->assertEquals($originalContent, $decryptedContentByBob);
    }


    /**
     * @depends generateKeyPair__withOneOfKeyPairTypes__returnsValidKeyPair
     *
     * @param VirgilKeyPair[] $keyPairs
     *
     * @test
     *
     * @return array
     */
    public function encryptStream__withRecipients__encryptsDataFromInputStreamToOutputStream(
        array $keyPairs
    ) {
        list($aliceKeyPair, $bobKeyPair) = $keyPairs;

        $content = 'data_to_encrypt';
        $source = fopen('php://memory', 'r+');
        $sin = fopen('php://memory', 'r+');

        fwrite($source, $content);

        $aliceReceiverId = 'SALGH&';
        $bobReceiverId = 'ZLKG&';

        $streamCipher = $this->cryptoService->createStreamCipher();

        $streamCipher->addKeyRecipient($aliceReceiverId, $aliceKeyPair->getPublicKey());
        $streamCipher->addKeyRecipient($bobReceiverId, $bobKeyPair->getPublicKey());
        $streamInputOutput = $streamCipher->createInputOutput($source, $sin);


        $streamCipher->encrypt($streamInputOutput);


        rewind($sin);
        $encryptedContent = stream_get_contents($sin);
        $this->assertNotEmpty($encryptedContent);


        return [
            $content,
            $encryptedContent,
            $streamCipher,
            [
                $aliceReceiverId,
                $aliceKeyPair->getPrivateKey(),
            ],
            [
                $bobReceiverId,
                $bobKeyPair->getPrivateKey(),
            ],
        ];
    }


    /**
     * @depends encryptStream__withRecipients__encryptsDataFromInputStreamToOutputStream
     *
     * @param array $encryptWithRecipientsData
     *
     * @test
     */
    public function decryptWithKeyStream__withRecipients__decryptsEncryptedDataFromInputStreamToOutputStream(
        array $encryptWithRecipientsData
    ) {
        list($content, $encryptedContent, $streamCipher, $alicePrivateKeyWithId, $bobPrivateKeyWithId) = $encryptWithRecipientsData;
        list($aliceReceiverId, $alicePrivateKey) = $alicePrivateKeyWithId;
        list($bobReceiverId, $bobPrivateKey) = $bobPrivateKeyWithId;

        $source = fopen('php://memory', 'r+');
        fwrite($source, $encryptedContent);


        $sin = fopen('php://memory', 'r+');
        $streamCipher->decryptWithKey(
            $streamCipher->createInputOutput($source, $sin),
            $aliceReceiverId,
            $alicePrivateKey
        );

        rewind($sin);
        $decryptedContentByAlice = stream_get_contents($sin);

        $sin = fopen('php://memory', 'r+');
        $streamCipher->decryptWithKey(
            $streamCipher->createInputOutput($source, $sin),
            $bobReceiverId,
            $bobPrivateKey
        );

        rewind($sin);
        $decryptedContentByBob = stream_get_contents($sin);


        $this->assertEquals($content, $decryptedContentByAlice);
        $this->assertEquals($content, $decryptedContentByBob);
    }
}
