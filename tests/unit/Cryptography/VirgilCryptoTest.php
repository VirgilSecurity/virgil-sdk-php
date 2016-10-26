<?php
namespace Virgil\Tests\Unit\Cryptography;

use PHPUnit\Framework\TestCase;
use Virgil\SDK\Cryptography\CryptoAPI\VirgilCryptoAPI;
use Virgil\SDK\Cryptography\VirgilCrypto;
use Virgil\SDK\Cryptography\VirgilCryptoType;
use Virgil\SDK\Cryptography\VirgilHashAlgorithmType;
use Virgil\SDK\Cryptography\VirgilKey;
use Virgil\SDK\Cryptography\VirgilKeyPair;
use Virgil\SDK\Cryptography\CryptoAPI\VirgilKeyPair as CryptoAPIVirgilKeyPair;

class VirgilCryptoTest extends TestCase
{
    public function testThisShouldGenerateKeys()
    {
        $publicKey = 'public_key';
        $privateKey = 'private_key';

        $expectedKeys = new VirgilKeyPair(
            new VirgilKey('public_key_hash', 'publicDER_key'), new VirgilKey('public_key_hash', 'privateDER_key')
        );

        $virgilKeyPair = new CryptoAPIVirgilKeyPair($publicKey, $privateKey);

        /** @var VirgilCryptoAPI $virgilCryptoAPIMock */
        $virgilCryptoAPIMock = $this->createPartialMock(VirgilCryptoAPI::class,
            ['generate', 'publicKeyToDER', 'privateKeyToDER', 'computeKeyHash']);


        $virgilCryptoAPIMock->expects($this->once())
            ->method('generate')
            ->with($this->identicalTo(VirgilCryptoType::DefaultType))
            ->willReturn($virgilKeyPair);

        $virgilCryptoAPIMock->expects($this->once())
            ->method('publicKeyToDER')
            ->with($publicKey)
            ->willReturn("publicDER_key");

        $virgilCryptoAPIMock->expects($this->once())
            ->method('privateKeyToDER')
            ->with($privateKey)
            ->willReturn("privateDER_key");

        $virgilCryptoAPIMock->expects($this->once())
            ->method('computeKeyHash')
            ->with('publicDER_key', VirgilHashAlgorithmType::DefaultType)
            ->willReturn('public_key_hash');

        $virgilCrypto = new VirgilCrypto($virgilCryptoAPIMock);

        /** @var \Virgil\SDK\Cryptography\VirgilKeyPair $actualKeys */
        $actualKeys = $virgilCrypto->generateKeys();

        $this->assertEquals($expectedKeys, $actualKeys);
    }

    /**
     * @expectedException \Virgil\SDK\Cryptography\CryptoAPI\Exceptions\CipherException
     */
    public function testEncryptDecrypt()
    {
        $data = 'data_to_encrypt';
        $virgilCrypto = new VirgilCrypto(new VirgilCryptoAPI());
        $keys = $virgilCrypto->generateKeys();
        $keys2 = $virgilCrypto->generateKeys();
        $keys3 = $virgilCrypto->generateKeys();
        $encryptedData = $virgilCrypto->encrypt($data, [$keys->getPublicKey(), $keys2->getPublicKey()]);

        $this->assertEquals($data, $virgilCrypto->decrypt($encryptedData, $keys->getPrivateKey()));
        $this->assertEquals($data, $virgilCrypto->decrypt($encryptedData, $keys2->getPrivateKey()));
        $this->assertNotEquals($data, $virgilCrypto->decrypt($encryptedData, $keys3->getPrivateKey()));
    }

    public function testStreamEncryptDecrypt()
    {
        $data = 'data_to_encrypt';
        $source = fopen('php://memory', 'r+');
        $sin = fopen('php://memory', 'r+');
        fwrite($source, $data);
        $virgilCrypto = new VirgilCrypto(new VirgilCryptoAPI());
        $keys = $virgilCrypto->generateKeys();
        $keys2 = $virgilCrypto->generateKeys();

        $virgilCrypto->streamEncrypt($source, $sin, [$keys->getPublicKey(), $keys2->getPublicKey()]);
        rewind($sin);
        $this->assertNotEquals($data, stream_get_contents($sin));

        $source = fopen('php://memory', 'w');
        $virgilCrypto->streamDecrypt($sin, $source, $keys->getPrivateKey());
        rewind($source);
        $this->assertEquals($data, stream_get_contents($source));

        $source = fopen('php://memory', 'w');
        $virgilCrypto->streamDecrypt($sin, $source, $keys2->getPrivateKey());
        rewind($source);
        $this->assertEquals($data, stream_get_contents($source));
    }
}