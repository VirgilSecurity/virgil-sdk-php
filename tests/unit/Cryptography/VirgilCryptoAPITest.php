<?php
namespace Virgil\Tests\Unit\Cryptography;

use PHPUnit\Framework\TestCase;
use Virgil\SDK\Cryptography\CryptoAPI\VirgilCryptoAPI;
use Virgil\SDK\Cryptography\VirgilCryptoType;

class VirgilCryptoAPITest extends TestCase
{
    public function testThisShouldGenerateKeys()
    {
        $virgilCryptoAPI = new VirgilCryptoAPI();
        $key = $virgilCryptoAPI->generate(VirgilCryptoType::DefaultType);
        $key2 = $virgilCryptoAPI->generate(VirgilCryptoType::DefaultType);

        $this->assertTrue($virgilCryptoAPI->isKeyPairMatch($key->getPublicKey(), $key->getPrivateKey()));
        $this->assertFalse($virgilCryptoAPI->isKeyPairMatch($key2->getPublicKey(), $key->getPrivateKey()));
    }

    /**
     * @expectedException \Virgil\SDK\Cryptography\CryptoAPI\Exceptions\GenerateException
     */
    public function testWrongTypeShouldThrowExceptionOnGenerateKeys()
    {
        $virgilCrypto = new VirgilCryptoAPI();
        $virgilCrypto->generate(21);
    }

    /**
     * @expectedException \Virgil\SDK\Cryptography\CryptoAPI\Exceptions\PublicKeyToDERException
     */
    public function testWrongPublicKeyShouldThrowExceptionOnPublicKeyToDER()
    {
        $virgilCrypto = new VirgilCryptoAPI();
        $virgilCrypto->publicKeyToDER('wrong key');
    }

    /**
     * @expectedException \Virgil\SDK\Cryptography\CryptoAPI\Exceptions\PrivateKeyToDERException
     */
    public function testWrongPrivateKeyShouldThrowExceptionOnPrivateKeyToDER()
    {
        $virgilCrypto = new VirgilCryptoAPI();
        $virgilCrypto->privateKeyToDER('wrong key');
    }

    /**
     * @expectedException \Virgil\SDK\Cryptography\CryptoAPI\Exceptions\ComputePublicKeyHashException
     */
    public function testWrongAlgorithmShouldThrowExceptionOnComputePublicKeyHash()
    {
        $virgilCrypto = new VirgilCryptoAPI();
        $virgilCrypto->computeKeyHash('wrong key', 'wrong algorithm');
    }

    public function testExtractPublicKey()
    {
        $virgilCrypto = new VirgilCryptoAPI();
        $keys = $virgilCrypto->generate(VirgilCryptoType::DefaultType);
        $keys2 = $virgilCrypto->generate(VirgilCryptoType::EC_BP384R1);
        $extractedPublicKey = $virgilCrypto->extractPublicKey($keys->getPrivateKey(), '');
        $this->assertEquals($keys->getPublicKey(), $extractedPublicKey);

        $extractedPublicKey = $virgilCrypto->extractPublicKey($keys2->getPrivateKey(), '');
        $this->assertNotEquals($keys->getPublicKey(), $extractedPublicKey);
    }

    /**
     * @expectedException \Virgil\SDK\Cryptography\CryptoAPI\Exceptions\ExtractPublicKeyException
     */
    public function testWrongPrivateKeyShouldThrowExceptionOnExtractPublicKey()
    {
        $virgilCrypto = new VirgilCryptoAPI();
        $virgilCrypto->extractPublicKey('wrong private key', '');
    }

    public function testEncryptPrivateKey()
    {
        $virgilCrypto = new VirgilCryptoAPI();
        $keys = $virgilCrypto->generate(VirgilCryptoType::DefaultType);
        $encryptedPrivateKey = $virgilCrypto->encryptPrivateKey($keys->getPrivateKey(), 'qwerty');
        $this->assertNotEquals($keys->getPrivateKey(), $encryptedPrivateKey);
    }

    public function testDecryptPrivateKey()
    {
        $virgilCrypto = new VirgilCryptoAPI();
        $keys = $virgilCrypto->generate(VirgilCryptoType::DefaultType);
        $encryptedPrivateKey = $virgilCrypto->encryptPrivateKey($keys->getPrivateKey(), 'qwerty');
        $this->assertNotEquals($keys->getPrivateKey(), $encryptedPrivateKey);
        $decryptedPrivateKey = $virgilCrypto->decryptPrivateKey($encryptedPrivateKey, 'qwerty');
        $this->assertEquals($keys->getPrivateKey(), $decryptedPrivateKey);
    }


    /**
     * @expectedException \Virgil\SDK\Cryptography\CryptoAPI\Exceptions\EncryptPrivateKeyException
     */
    public function testWrongPrivateKeyOrEmptyPasswordShouldThrowExceptionOnEncryptPrivateKey()
    {
        $virgilCrypto = new VirgilCryptoAPI();
        $virgilCrypto->encryptPrivateKey('wrong private key', '');
    }

    /**
     * @expectedException \Virgil\SDK\Cryptography\CryptoAPI\Exceptions\DecryptPrivateKeyException
     */
    public function testWrongPrivateKeyOrEmptyPasswordShouldThrowExceptionOnDecryptPrivateKey()
    {
        $virgilCrypto = new VirgilCryptoAPI();
        $virgilCrypto->decryptPrivateKey('wrong private key', '');
    }
}