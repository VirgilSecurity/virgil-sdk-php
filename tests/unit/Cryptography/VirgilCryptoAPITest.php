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
        $virgilCrypto->publicKeyToDER("wrong key");
    }

    /**
     * @expectedException \Virgil\SDK\Cryptography\CryptoAPI\Exceptions\PrivateKeyToDERException
     */
    public function testWrongPrivateKeyShouldThrowExceptionOnPrivateKeyToDER()
    {
        $virgilCrypto = new VirgilCryptoAPI();
        $virgilCrypto->privateKeyToDER("wrong key");
    }

    /**
     * @expectedException \Virgil\SDK\Cryptography\CryptoAPI\Exceptions\ComputePublicKeyHashException
     */
    public function testWrongAlgorithmShouldThrowExceptionOnComputePublicKeyHash()
    {
        $virgilCrypto = new VirgilCryptoAPI();
        $virgilCrypto->computeKeyHash('wrong key', 'wrong algorithm');
    }
}