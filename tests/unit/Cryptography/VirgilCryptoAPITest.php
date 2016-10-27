<?php
namespace Virgil\Tests\Unit\Cryptography;

use PHPUnit\Framework\TestCase;
use Virgil\SDK\Cryptography\CryptoAPI\Cipher\VirgilCipher;
use Virgil\SDK\Cryptography\CryptoAPI\Cipher\VirgilStreamCipher;
use Virgil\SDK\Cryptography\CryptoAPI\VirgilCryptoAPI;
use Virgil\SDK\Cryptography\VirgilCryptoType;

class VirgilCryptoAPITest extends TestCase
{
    /** @var VirgilCryptoAPI */
    private $virgilCryptoAPI;

    public function setUp()
    {
        $this->virgilCryptoAPI = new VirgilCryptoAPI();
        parent::setUp();
    }

    public function testThisShouldGenerateKeys()
    {
        $key = $this->virgilCryptoAPI->generate(VirgilCryptoType::DefaultType);
        $key2 = $this->virgilCryptoAPI->generate(VirgilCryptoType::DefaultType);

        $this->assertTrue($this->virgilCryptoAPI->isKeyPairMatch($key->getPublicKey(), $key->getPrivateKey()));
        $this->assertFalse($this->virgilCryptoAPI->isKeyPairMatch($key2->getPublicKey(), $key->getPrivateKey()));
    }

    /**
     * @expectedException \Virgil\SDK\Cryptography\CryptoAPI\Exceptions\GenerateException
     */
    public function testWrongTypeShouldThrowExceptionOnGenerateKeys()
    {
        $this->virgilCryptoAPI->generate(21);
    }

    /**
     * @expectedException \Virgil\SDK\Cryptography\CryptoAPI\Exceptions\PublicKeyToDERException
     */
    public function testWrongPublicKeyShouldThrowExceptionOnPublicKeyToDER()
    {
        $this->virgilCryptoAPI->publicKeyToDER('wrong key');
    }

    /**
     * @expectedException \Virgil\SDK\Cryptography\CryptoAPI\Exceptions\PrivateKeyToDERException
     */
    public function testWrongPrivateKeyShouldThrowExceptionOnPrivateKeyToDER()
    {
        $this->virgilCryptoAPI->privateKeyToDER('wrong key');
    }

    /**
     * @expectedException \Virgil\SDK\Cryptography\CryptoAPI\Exceptions\ComputePublicKeyHashException
     */
    public function testWrongAlgorithmShouldThrowExceptionOnComputePublicKeyHash()
    {
        $this->virgilCryptoAPI->computeKeyHash('wrong key', 'wrong algorithm');
    }

    public function testExtractPublicKey()
    {
        $keys = $this->virgilCryptoAPI->generate(VirgilCryptoType::DefaultType);
        $keys2 = $this->virgilCryptoAPI->generate(VirgilCryptoType::EC_BP384R1);
        $extractedPublicKey = $this->virgilCryptoAPI->extractPublicKey($keys->getPrivateKey(), '');
        $this->assertEquals($keys->getPublicKey(), $extractedPublicKey);

        $extractedPublicKey = $this->virgilCryptoAPI->extractPublicKey($keys2->getPrivateKey(), '');
        $this->assertNotEquals($keys->getPublicKey(), $extractedPublicKey);
    }

    /**
     * @expectedException \Virgil\SDK\Cryptography\CryptoAPI\Exceptions\ExtractPublicKeyException
     */
    public function testWrongPrivateKeyShouldThrowExceptionOnExtractPublicKey()
    {
        $this->virgilCryptoAPI->extractPublicKey('wrong private key', '');
    }

    public function testEncryptPrivateKey()
    {
        $keys = $this->virgilCryptoAPI->generate(VirgilCryptoType::DefaultType);
        $encryptedPrivateKey = $this->virgilCryptoAPI->encryptPrivateKey($keys->getPrivateKey(), 'qwerty');
        $this->assertNotEquals($keys->getPrivateKey(), $encryptedPrivateKey);
    }

    public function testDecryptPrivateKey()
    {
        $keys = $this->virgilCryptoAPI->generate(VirgilCryptoType::DefaultType);
        $encryptedPrivateKey = $this->virgilCryptoAPI->encryptPrivateKey($keys->getPrivateKey(), 'qwerty');
        $this->assertNotEquals($keys->getPrivateKey(), $encryptedPrivateKey);
        $decryptedPrivateKey = $this->virgilCryptoAPI->decryptPrivateKey($encryptedPrivateKey, 'qwerty');
        $this->assertEquals($keys->getPrivateKey(), $decryptedPrivateKey);
    }


    /**
     * @expectedException \Virgil\SDK\Cryptography\CryptoAPI\Exceptions\EncryptPrivateKeyException
     */
    public function testWrongPrivateKeyOrEmptyPasswordShouldThrowExceptionOnEncryptPrivateKey()
    {
        $this->virgilCryptoAPI->encryptPrivateKey('wrong private key', '');
    }

    /**
     * @expectedException \Virgil\SDK\Cryptography\CryptoAPI\Exceptions\DecryptPrivateKeyException
     */
    public function testWrongPrivateKeyOrEmptyPasswordShouldThrowExceptionOnDecryptPrivateKey()
    {
        $this->virgilCryptoAPI->decryptPrivateKey('wrong private key', '');
    }

    /**
     * @expectedException \Virgil\SDK\Cryptography\CryptoAPI\Exceptions\VerifyException
     */
    public function testWrongKeyOrSignatureShouldThrowExceptionOnVerify()
    {
        $data = 'data';
        $signature = 'wrong signature';
        $keys = $this->virgilCryptoAPI->generate(VirgilCryptoType::DefaultType);
        $isValid = $this->virgilCryptoAPI->verify($data, $signature, $keys->getPublicKey());
        $this->assertTrue($isValid);
    }

    /**
     * @expectedException \Virgil\SDK\Cryptography\CryptoAPI\Exceptions\VerifyException
     */
    public function testWrongKeyOrSignatureShouldThrowExceptionOnStreamVerify()
    {
        $source = fopen('php://memory', 'r+');
        $data = 'data';
        fwrite($source, $data);
        $signature = 'wrong signature';
        $keys = $this->virgilCryptoAPI->generate(VirgilCryptoType::DefaultType);
        $isValid = $this->virgilCryptoAPI->streamVerify($source, $signature, $keys->getPublicKey());
        $this->assertTrue($isValid);
    }

    /**
     * @expectedException \Virgil\SDK\Cryptography\CryptoAPI\Exceptions\SignException
     */
    public function testWrongPrivateKeyShouldThrowExceptionOnSign()
    {
        $this->virgilCryptoAPI->sign('data', 'wrong private key');
    }

    public function testDataIsSignedAndVerified()
    {
        $data = 'data';
        $keys = $this->virgilCryptoAPI->generate(VirgilCryptoType::DefaultType);
        $signature = $this->virgilCryptoAPI->sign($data, $keys->getPrivateKey());
        $isValid = $this->virgilCryptoAPI->verify($data, $signature, $keys->getPublicKey());
        $this->assertTrue($isValid);

        $keys2 = $this->virgilCryptoAPI->generate(VirgilCryptoType::DefaultType);
        $isValid = $this->virgilCryptoAPI->verify($data, $signature, $keys2->getPublicKey());
        $this->assertFalse($isValid);
    }

    /**
     * @expectedException \Virgil\SDK\Cryptography\CryptoAPI\Exceptions\SignException
     */
    public function testWrongPrivateKeyShouldThrowExceptionOnStreamSign()
    {
        $source = fopen('php://memory', 'r+');
        $this->virgilCryptoAPI->streamSign($source, 'wrong private key');
    }

    public function testStreamIsSignedAndVerified()
    {
        $source = fopen('php://memory', 'r+');
        $data = 'data';
        fwrite($source, $data);
        $keys = $this->virgilCryptoAPI->generate(VirgilCryptoType::DefaultType);
        $signature = $this->virgilCryptoAPI->streamSign($source, $keys->getPrivateKey());
        $isValid = $this->virgilCryptoAPI->streamVerify($source, $signature, $keys->getPublicKey());
        $this->assertTrue($isValid);

        $keys2 = $this->virgilCryptoAPI->generate(VirgilCryptoType::DefaultType);
        $isValid = $this->virgilCryptoAPI->streamVerify($source, $signature, $keys2->getPublicKey());
        $this->assertFalse($isValid);
    }

    /**
     * @expectedException \Virgil\SDK\Cryptography\CryptoAPI\Exceptions\CipherException
     */
    public function testDataDecryptNoRecipients()
    {
        $data = 'data';
        $receiverId = 'SALGH&';
        $keys = $this->virgilCryptoAPI->generate(VirgilCryptoType::DefaultType);
        /** @var VirgilCipher $cipher */
        $cipher = $this->virgilCryptoAPI->cipher();
        $encryptedData = $cipher->encrypt($data);
        $cipher->decryptWithKey($encryptedData, $receiverId, $keys->getPrivateKey());
    }

    public function testDataEncryptAndDecrypt()
    {
        $data = 'data';
        $receiverId = 'SALGH&';
        $keys = $this->virgilCryptoAPI->generate(VirgilCryptoType::DefaultType);
        /** @var VirgilCipher $cipher */
        $cipher = $this->virgilCryptoAPI->cipher();
        $cipher->addKeyRecipient($receiverId, $keys->getPublicKey());
        $encryptedData = $cipher->encrypt($data);
        $this->assertEquals($data, $cipher->decryptWithKey($encryptedData, $receiverId, $keys->getPrivateKey()));
    }

    public function testStreamEncryptAndDecrypt()
    {
        $data = 'data_to_encrypt';
        $source = fopen('php://memory', 'r+');
        $sin = fopen('php://memory', 'r+');

        fwrite($source, $data);
        $receiverId = 'fsat3';
        $keys = $this->virgilCryptoAPI->generate(VirgilCryptoType::DefaultType);

        /** @var VirgilStreamCipher $streamCipher */
        $streamCipher = $this->virgilCryptoAPI->streamCipher();

        /** @var VirgilCipher $cipher */
        $cipher = $this->virgilCryptoAPI->cipher();

        $streamCipher->addKeyRecipient($receiverId, $keys->getPublicKey());
        $cipher->addKeyRecipient($receiverId, $keys->getPublicKey());

        $streamCipher->encrypt($source, $sin);

        rewind($sin);
        $this->assertEquals($data, $cipher->decryptWithKey(stream_get_contents($sin), $receiverId, $keys->getPrivateKey()));

        rewind($source);
        $streamCipher->decryptWithKey($sin, $source, $receiverId, $keys->getPrivateKey());

        rewind($source);
        $this->assertEquals($data, stream_get_contents($source));
    }
}