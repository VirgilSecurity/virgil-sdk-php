<?php
namespace Virgil\Tests\Unit\Cryptography;


use PHPUnit\Framework\TestCase;

use Virgil\Sdk\Cryptography\Constants\KeyPairType;
use Virgil\Sdk\Cryptography\Core\Cipher\VirgilCipher;
use Virgil\Sdk\Cryptography\Core\Cipher\VirgilStreamCipher;
use Virgil\Sdk\Cryptography\Core\VirgilCryptoService;

class VirgilCryptoServiceTest extends TestCase
{
    /** @var VirgilCryptoService */
    private $cryptoService;

    public function setUp()
    {
        $this->cryptoService = new VirgilCryptoService();
        parent::setUp();
    }

    public function testThisShouldGenerateKeys()
    {
        $key = $this->cryptoService->generateKeyPair(KeyPairType::FAST_EC_ED25519);
        $key2 = $this->cryptoService->generateKeyPair(KeyPairType::FAST_EC_ED25519);

        $this->assertTrue($this->cryptoService->isKeyPair($key->getPublicKey(), $key->getPrivateKey()));
        $this->assertFalse($this->cryptoService->isKeyPair($key2->getPublicKey(), $key->getPrivateKey()));
    }

    /**
     * @expectedException \Virgil\Sdk\Cryptography\Core\Exceptions\KeyPairGenerationException
     */
    public function testWrongTypeShouldThrowExceptionOnGenerateKeys()
    {
        $this->cryptoService->generateKeyPair(21);
    }

    /**
     * @expectedException \Virgil\Sdk\Cryptography\Core\Exceptions\PublicKeyToDerConvertingException
     */
    public function testWrongPublicKeyShouldThrowExceptionOnPublicKeyToDER()
    {
        $this->cryptoService->publicKeyToDer('wrong key');
    }

    /**
     * @expectedException \Virgil\Sdk\Cryptography\Core\Exceptions\PrivateKeyToDerConvertingException
     */
    public function testWrongPrivateKeyShouldThrowExceptionOnPrivateKeyToDER()
    {
        $this->cryptoService->privateKeyToDer('wrong key');
    }

    /**
     * @expectedException \Virgil\Sdk\Cryptography\Core\Exceptions\PublicKeyHashComputationException
     */
    public function testWrongAlgorithmShouldThrowExceptionOnComputePublicKeyHash()
    {
        $this->cryptoService->computeHash('wrong key', 'wrong algorithm');
    }

    public function testExtractPublicKey()
    {
        $keys = $this->cryptoService->generateKeyPair(KeyPairType::FAST_EC_ED25519);
        $keys2 = $this->cryptoService->generateKeyPair(KeyPairType::EC_BP384R1);
        $extractedPublicKey = $this->cryptoService->extractPublicKey($keys->getPrivateKey(), '');
        $this->assertEquals($keys->getPublicKey(), $extractedPublicKey);

        $extractedPublicKey = $this->cryptoService->extractPublicKey($keys2->getPrivateKey(), '');
        $this->assertNotEquals($keys->getPublicKey(), $extractedPublicKey);
    }

    /**
     * @expectedException \Virgil\Sdk\Cryptography\Core\Exceptions\PublicKeyExtractionException
     */
    public function testWrongPrivateKeyShouldThrowExceptionOnExtractPublicKey()
    {
        $this->cryptoService->extractPublicKey('wrong private key', '');
    }

    public function testEncryptPrivateKey()
    {
        $keys = $this->cryptoService->generateKeyPair(KeyPairType::FAST_EC_ED25519);
        $encryptedPrivateKey = $this->cryptoService->encryptPrivateKey($keys->getPrivateKey(), 'qwerty');
        $this->assertNotEquals($keys->getPrivateKey(), $encryptedPrivateKey);
    }

    public function testDecryptPrivateKey()
    {
        $keys = $this->cryptoService->generateKeyPair(KeyPairType::FAST_EC_ED25519);
        $encryptedPrivateKey = $this->cryptoService->encryptPrivateKey($keys->getPrivateKey(), 'qwerty');
        $this->assertNotEquals($keys->getPrivateKey(), $encryptedPrivateKey);
        $decryptedPrivateKey = $this->cryptoService->decryptPrivateKey($encryptedPrivateKey, 'qwerty');
        $this->assertEquals($keys->getPrivateKey(), $decryptedPrivateKey);
    }


    /**
     * @expectedException \Virgil\Sdk\Cryptography\Core\Exceptions\PrivateKeyEncryptionException
     */
    public function testWrongPrivateKeyOrEmptyPasswordShouldThrowExceptionOnEncryptPrivateKey()
    {
        $this->cryptoService->encryptPrivateKey('wrong private key', '');
    }

    /**
     * @expectedException \Virgil\Sdk\Cryptography\Core\Exceptions\PrivateKeyDecryptionException
     */
    public function testWrongPrivateKeyOrEmptyPasswordShouldThrowExceptionOnDecryptPrivateKey()
    {
        $this->cryptoService->decryptPrivateKey('wrong private key', '');
    }

    /**
     * @expectedException \Virgil\Sdk\Cryptography\Core\Exceptions\ContentVerificationException
     */
    public function testWrongKeyOrSignatureShouldThrowExceptionOnVerify()
    {
        $data = 'data';
        $signature = 'wrong signature';
        $keys = $this->cryptoService->generateKeyPair(KeyPairType::FAST_EC_ED25519);
        $isValid = $this->cryptoService->verify($data, $signature, $keys->getPublicKey());
        $this->assertTrue($isValid);
    }

    /**
     * @expectedException \Virgil\Sdk\Cryptography\Core\Exceptions\ContentVerificationException
     */
    public function testWrongKeyOrSignatureShouldThrowExceptionOnStreamVerify()
    {
        $source = fopen('php://memory', 'r+');
        $data = 'data';
        fwrite($source, $data);
        $signature = 'wrong signature';
        $keys = $this->cryptoService->generateKeyPair(KeyPairType::FAST_EC_ED25519);
        $isValid = $this->cryptoService->verifyStream($source, $signature, $keys->getPublicKey());
        $this->assertTrue($isValid);
    }

    /**
     * @expectedException \Virgil\Sdk\Cryptography\Core\Exceptions\ContentSigningException
     */
    public function testWrongPrivateKeyShouldThrowExceptionOnSign()
    {
        $this->cryptoService->sign('data', 'wrong private key');
    }

    public function testDataIsSignedAndVerified()
    {
        $data = 'data';
        $keys = $this->cryptoService->generateKeyPair(KeyPairType::FAST_EC_ED25519);
        $signature = $this->cryptoService->sign($data, $keys->getPrivateKey());
        $isValid = $this->cryptoService->verify($data, $signature, $keys->getPublicKey());
        $this->assertTrue($isValid);

        $keys2 = $this->cryptoService->generateKeyPair(KeyPairType::FAST_EC_ED25519);
        $isValid = $this->cryptoService->verify($data, $signature, $keys2->getPublicKey());
        $this->assertFalse($isValid);
    }

    /**
     * @expectedException \Virgil\Sdk\Cryptography\Core\Exceptions\ContentSigningException
     */
    public function testWrongPrivateKeyShouldThrowExceptionOnStreamSign()
    {
        $source = fopen('php://memory', 'r+');
        $this->cryptoService->signStream($source, 'wrong private key');
    }

    public function testStreamIsSignedAndVerified()
    {
        $source = fopen('php://memory', 'r+');
        $data = 'data';
        fwrite($source, $data);
        $keys = $this->cryptoService->generateKeyPair(KeyPairType::FAST_EC_ED25519);
        $signature = $this->cryptoService->signStream($source, $keys->getPrivateKey());
        $isValid = $this->cryptoService->verifyStream($source, $signature, $keys->getPublicKey());
        $this->assertTrue($isValid);

        $keys2 = $this->cryptoService->generateKeyPair(KeyPairType::FAST_EC_ED25519);
        $isValid = $this->cryptoService->verifyStream($source, $signature, $keys2->getPublicKey());
        $this->assertFalse($isValid);
    }

    /**
     * @expectedException \Virgil\Sdk\Cryptography\Core\Exceptions\CipherException
     */
    public function testDataDecryptNoRecipients()
    {
        $data = 'data';
        $receiverId = 'SALGH&';
        $keys = $this->cryptoService->generateKeyPair(KeyPairType::FAST_EC_ED25519);
        /** @var VirgilCipher $cipher */
        $cipher = $this->cryptoService->createCipher();
        $encryptedData = $cipher->encrypt($cipher->createInputOutput($data));
        $cipher->decryptWithKey($cipher->createInputOutput($encryptedData), $receiverId, $keys->getPrivateKey());
    }

    public function testDataEncryptAndDecrypt()
    {
        $data = 'data';
        $receiverId = 'SALGH&';
        $keys = $this->cryptoService->generateKeyPair(KeyPairType::FAST_EC_ED25519);
        /** @var VirgilCipher $cipher */
        $cipher = $this->cryptoService->createCipher();
        $cipher->addKeyRecipient($receiverId, $keys->getPublicKey());
        $encryptedData = $cipher->encrypt($cipher->createInputOutput($data));
        $this->assertEquals($data, $cipher->decryptWithKey($cipher->createInputOutput($encryptedData), $receiverId, $keys->getPrivateKey()));
    }

    public function testStreamEncryptAndDecrypt()
    {
        $data = 'data_to_encrypt';
        $source = fopen('php://memory', 'r+');
        $sin = fopen('php://memory', 'r+');

        fwrite($source, $data);
        $receiverId = 'fsat3';
        $keys = $this->cryptoService->generateKeyPair(KeyPairType::FAST_EC_ED25519);

        /** @var VirgilStreamCipher $streamCipher */
        $streamCipher = $this->cryptoService->createStreamCipher();

        /** @var VirgilCipher $cipher */
        $cipher = $this->cryptoService->createCipher();

        $streamCipher->addKeyRecipient($receiverId, $keys->getPublicKey());
        $cipher->addKeyRecipient($receiverId, $keys->getPublicKey());

        $streamCipher->encrypt($streamCipher->createInputOutput($source, $sin));

        rewind($sin);
        $this->assertEquals($data, $cipher->decryptWithKey($cipher->createInputOutput(stream_get_contents($sin)), $receiverId, $keys->getPrivateKey()));

        rewind($source);
        $streamCipher->decryptWithKey($streamCipher->createInputOutput($sin, $source), $receiverId, $keys->getPrivateKey());

        rewind($source);
        $this->assertEquals($data, stream_get_contents($source));
    }
}
