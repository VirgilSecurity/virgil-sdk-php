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

        $virgilCryptoAPIMock = $this->createPartialMock(VirgilCryptoAPI::class,
            ['generate', 'publicKeyToDER', 'privateKeyToDER', 'computeKeyHash']);

        $virgilCryptoAPIMock->expects($this->once())
            ->method('generate')
            ->with(VirgilCryptoType::DefaultType)
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

    public function testCalculateFingerprint()
    {
        $content = 'fingerprint_content';

        $virgilCryptoAPIMock = $this->createMock(VirgilCryptoAPI::class);

        $virgilCryptoAPIMock->expects($this->once())
            ->method('computeKeyHash')
            ->with($content, VirgilHashAlgorithmType::SHA256)
            ->willReturn('fingerprint_content_hash');

        $virgilCrypto = new VirgilCrypto($virgilCryptoAPIMock);
        $this->assertEquals('fingerprint_content_hash', $virgilCrypto->calculateFingerprint($content));
    }

    public function testDataSignVerify()
    {
        $content = 'data_to_sign';

        $virgilCryptoAPIMock = $this->createPartialMock(VirgilCryptoAPI::class, ['sign', 'verify']);
        $virgilCrypto = new VirgilCrypto($virgilCryptoAPIMock);

        $keys = $virgilCrypto->generateKeys();

        $virgilCryptoAPIMock->expects($this->once())
            ->method('sign')
            ->with($content, $keys->getPrivateKey()->getValue())
            ->willReturn('data_signature');

        $virgilCryptoAPIMock->expects($this->once())
            ->method('verify')
            ->with($content, 'data_signature', $keys->getPublicKey()->getValue())
            ->willReturn(true);

        $signature = $virgilCrypto->sign($content, $keys->getPrivateKey());
        $this->assertTrue($virgilCrypto->verify($content, $signature, $keys->getPublicKey()));
    }

    public function testStreamSignVerify()
    {
        $content = 'data_to_encrypt';
        $source = fopen('php://memory', 'r');
        fwrite($source, $content);

        $virgilCryptoAPIMock = $this->createPartialMock(VirgilCryptoAPI::class, ['streamSign', 'streamVerify']);
        $virgilCrypto = new VirgilCrypto($virgilCryptoAPIMock);

        $keys = $virgilCrypto->generateKeys();

        $virgilCryptoAPIMock->expects($this->once())
            ->method('streamSign')
            ->with($source, $keys->getPrivateKey()->getValue())
            ->willReturn('data_signature');

        $virgilCryptoAPIMock->expects($this->once())
            ->method('streamVerify')
            ->with($source, 'data_signature', $keys->getPublicKey()->getValue())
            ->willReturn(true);

        $signature = $virgilCrypto->streamSign($source, $keys->getPrivateKey());
        $this->assertTrue($virgilCrypto->streamVerify($source, $signature, $keys->getPublicKey()));
    }

    public function testExtractPublicKeyFromPrivateOne()
    {
        $virgilCrypto = new VirgilCrypto(new VirgilCryptoAPI());

        $keys = $virgilCrypto->generateKeys();

        $this->assertEquals($keys->getPublicKey(), $virgilCrypto->extractPublicKey($keys->getPrivateKey()));
    }

    public function testExportPublicKey()
    {
        $publicKeyDERvalue = 'public_key_der_value';
        $virgilCryptoAPIMock = $this->createPartialMock(VirgilCryptoAPI::class, ['publicKeyToDER']);

        $virgilCrypto = new VirgilCrypto($virgilCryptoAPIMock);

        $keys = $virgilCrypto->generateKeys();

        $virgilCryptoAPIMock->expects($this->once())
            ->method('publicKeyToDER')
            ->with($keys->getPublicKey()->getValue())
            ->willReturn($publicKeyDERvalue);

        $this->assertEquals($publicKeyDERvalue, $virgilCrypto->exportPublicKey($keys->getPublicKey()));
    }

    public function testExportPrivateKey()
    {
        $privateKeyDERvalue = 'private_key_der_value';
        $virgilCryptoAPIMock = $this->createPartialMock(VirgilCryptoAPI::class, ['privateKeyToDER']);

        $virgilCrypto = new VirgilCrypto($virgilCryptoAPIMock);

        $keys = $virgilCrypto->generateKeys();

        $virgilCryptoAPIMock->expects($this->once())
            ->method('privateKeyToDER')
            ->with($keys->getPrivateKey()->getValue())
            ->willReturn($privateKeyDERvalue);

        $this->assertEquals($privateKeyDERvalue, $virgilCrypto->exportPrivateKey($keys->getPrivateKey()));
    }

    public function testExportPrivateKeyWithPassword()
    {
        $privateKeyDERvalue = 'private_key_der_value';
        $password = 'secure_password';
        $virgilCryptoAPIMock = $this->createPartialMock(VirgilCryptoAPI::class, ['privateKeyToDER']);

        $virgilCrypto = new VirgilCrypto($virgilCryptoAPIMock);

        $keys = $virgilCrypto->generateKeys();

        $virgilCryptoAPIMock->expects($this->once())
            ->method('privateKeyToDER')
            ->with($keys->getPrivateKey()->getValue(), $password)
            ->willReturn($privateKeyDERvalue);

        $this->assertEquals($privateKeyDERvalue, $virgilCrypto->exportPrivateKey($keys->getPrivateKey(), $password));
    }

    public function testImportPrivateKey()
    {
        $virgilCryptoAPIMock = $this->createPartialMock(VirgilCryptoAPI::class, ['computeKeyHash', 'extractPublicKey']);

        $virgilCrypto = new VirgilCrypto($virgilCryptoAPIMock);

        $keys = $virgilCrypto->generateKeys();

        $exportedKey = $virgilCrypto->exportPrivateKey($keys->getPrivateKey());

        $expectedPrivateKey = new VirgilKey(
            'fingerprint_content_hash',
            $exportedKey
        );

        $virgilCryptoAPIMock->expects($this->once())
            ->method('computeKeyHash')
            ->with('extracted_public_key', VirgilHashAlgorithmType::SHA256)
            ->willReturn('fingerprint_content_hash');

        $virgilCryptoAPIMock->expects($this->once())
            ->method('extractPublicKey')
            ->with($exportedKey, '')
            ->willReturn('extracted_public_key');

        $this->assertEquals($expectedPrivateKey, $virgilCrypto->importPrivateKey($exportedKey));
    }

    public function testImportPrivateKeyWithPassword()
    {
        $password = 'secure_password';

        $virgilCryptoAPIMock = $this->createPartialMock(VirgilCryptoAPI::class, ['computeKeyHash', 'extractPublicKey']);

        $virgilCrypto = new VirgilCrypto($virgilCryptoAPIMock);

        $keys = $virgilCrypto->generateKeys();

        $exportedKey = $virgilCrypto->exportPrivateKey($keys->getPrivateKey(), $password);

        $expectedPrivateKey = new VirgilKey(
            'fingerprint_content_hash',
            $keys->getPrivateKey()->getValue()
        );

        $virgilCryptoAPIMock->expects($this->once())
            ->method('computeKeyHash')
            ->with('extracted_public_key', VirgilHashAlgorithmType::SHA256)
            ->willReturn('fingerprint_content_hash');

        $virgilCryptoAPIMock->expects($this->once())
            ->method('extractPublicKey')
            ->with($keys->getPrivateKey()->getValue(), '')
            ->willReturn('extracted_public_key');

        $this->assertEquals($expectedPrivateKey, $virgilCrypto->importPrivateKey($exportedKey, $password));
    }


    public function testImportPublicKey()
    {
        $virgilCryptoAPIMock = $this->createPartialMock(VirgilCryptoAPI::class, ['computeKeyHash']);

        $virgilCrypto = new VirgilCrypto($virgilCryptoAPIMock);

        $keys = $virgilCrypto->generateKeys();

        $exportedKey = $virgilCrypto->exportPublicKey($keys->getPublicKey());

        $expectedPublicKey = new VirgilKey(
            'fingerprint_content_hash',
            $exportedKey
        );

        $virgilCryptoAPIMock->expects($this->once())
            ->method('computeKeyHash')
            ->with($exportedKey, VirgilHashAlgorithmType::SHA256)
            ->willReturn('fingerprint_content_hash');

        $this->assertEquals($expectedPublicKey, $virgilCrypto->importPublicKey($exportedKey));
    }
}