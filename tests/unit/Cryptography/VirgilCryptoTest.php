<?php
namespace Virgil\Tests\Unit\Cryptography;

use PHPUnit\Framework\TestCase;
use Virgil\SDK\Buffer;
use Virgil\SDK\Cryptography\CryptoAPI\VirgilCryptoApi;
use Virgil\SDK\Cryptography\VirgilCrypto;
use Virgil\SDK\Cryptography\KeyPairType;
use Virgil\SDK\Cryptography\HashAlgorithm;
use Virgil\SDK\Cryptography\VirgilKeyPair;
use Virgil\SDK\Cryptography\CryptoAPI\VirgilKeyPair as CryptoAPIVirgilKeyPair;
use Virgil\SDK\Cryptography\VirgilPrivateKey;
use Virgil\SDK\Cryptography\VirgilPublicKey;

class VirgilCryptoTest extends TestCase
{
    public function testThisShouldGenerateKeys()
    {
        $publicKey = 'public_key';
        $privateKey = 'private_key';

        $expectedKeys = new VirgilKeyPair(
            new VirgilPublicKey('public_key_hash', 'publicDER_key'), new VirgilPrivateKey('public_key_hash', 'privateDER_key')
        );

        $virgilKeyPair = new CryptoAPIVirgilKeyPair($publicKey, $privateKey);

        $virgilCryptoAPIMock = $this->createPartialMock(VirgilCryptoApi::class,
            ['generate', 'publicKeyToDER', 'privateKeyToDER', 'computeHash']);

        $virgilCryptoAPIMock->expects($this->once())
            ->method('generate')
            ->with(KeyPairType::DefaultType)
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
            ->method('computeHash')
            ->with('publicDER_key', HashAlgorithm::DefaultType)
            ->willReturn('public_key_hash');

        $virgilCrypto = new VirgilCrypto($virgilCryptoAPIMock);

        /** @var \Virgil\SDK\Cryptography\VirgilKeyPair $actualKeys */
        $actualKeys = $virgilCrypto->generateKeys();

        $this->assertEquals($expectedKeys, $actualKeys);
    }

    public function testEncryptDecrypt()
    {
        $data = new Buffer('data_to_encrypt');
        $virgilCrypto = new VirgilCrypto();
        $keys = $virgilCrypto->generateKeys();
        $keys2 = $virgilCrypto->generateKeys();
        $encryptedData = $virgilCrypto->encrypt($data->getData(), [$keys->getPublicKey(), $keys2->getPublicKey()]);

        $this->assertEquals($data, $virgilCrypto->decrypt($encryptedData, $keys->getPrivateKey()));
        $this->assertEquals($data, $virgilCrypto->decrypt($encryptedData, $keys2->getPrivateKey()));
    }

    public function testStreamEncryptDecrypt()
    {
        $data = 'data_to_encrypt';
        $source = fopen('php://memory', 'r+');
        $sin = fopen('php://memory', 'r+');
        fwrite($source, $data);
        $virgilCrypto = new VirgilCrypto();
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
        $expectedFingerprint = new Buffer('fingerprint_content_hash');

        $virgilCryptoAPIMock = $this->createMock(VirgilCryptoApi::class);

        $virgilCryptoAPIMock->expects($this->once())
            ->method('computeHash')
            ->with($content, HashAlgorithm::SHA256)
            ->willReturn('fingerprint_content_hash');

        $virgilCrypto = new VirgilCrypto($virgilCryptoAPIMock);
        $this->assertEquals($expectedFingerprint, $virgilCrypto->calculateFingerprint(new Buffer($content)));
    }

    public function testDataSignVerify()
    {
        $content = 'data_to_sign';

        $virgilCryptoAPIMock = $this->createPartialMock(VirgilCryptoApi::class, ['sign', 'verify']);
        $virgilCrypto = new VirgilCrypto($virgilCryptoAPIMock);

        $keys = $virgilCrypto->generateKeys();

        $virgilCryptoAPIMock->expects($this->once())
            ->method('sign')
            ->with($content, $keys->getPrivateKey()->getValue()->getData())
            ->willReturn('data_signature');

        $virgilCryptoAPIMock->expects($this->once())
            ->method('verify')
            ->with($content, 'data_signature', $keys->getPublicKey()->getValue()->getData())
            ->willReturn(true);

        $signature = $virgilCrypto->sign($content, $keys->getPrivateKey());
        $this->assertTrue($virgilCrypto->verify($content, $signature, $keys->getPublicKey()));
    }

    public function testStreamSignVerify()
    {
        $content = 'data_to_encrypt';
        $source = fopen('php://memory', 'r');
        fwrite($source, $content);

        $virgilCryptoAPIMock = $this->createPartialMock(VirgilCryptoApi::class, ['streamSign', 'streamVerify']);
        $virgilCrypto = new VirgilCrypto($virgilCryptoAPIMock);

        $keys = $virgilCrypto->generateKeys();

        $virgilCryptoAPIMock->expects($this->once())
            ->method('streamSign')
            ->with($source, $keys->getPrivateKey()->getValue()->getData())
            ->willReturn('data_signature');

        $virgilCryptoAPIMock->expects($this->once())
            ->method('streamVerify')
            ->with($source, 'data_signature', $keys->getPublicKey()->getValue()->getData())
            ->willReturn(true);

        $signature = $virgilCrypto->streamSign($source, $keys->getPrivateKey());
        $this->assertTrue($virgilCrypto->streamVerify($source, $signature, $keys->getPublicKey()));
    }

    public function testExtractPublicKeyFromPrivateOne()
    {
        $virgilCrypto = new VirgilCrypto();

        $keys = $virgilCrypto->generateKeys();

        $this->assertEquals($keys->getPublicKey(), $virgilCrypto->extractPublicKey($keys->getPrivateKey()));
    }

    public function testExportPublicKey()
    {
        $publicKeyDERvalue = 'public_key_der_value';
        $virgilCryptoAPIMock = $this->createPartialMock(VirgilCryptoApi::class, ['publicKeyToDER']);

        $virgilCrypto = new VirgilCrypto($virgilCryptoAPIMock);

        $keys = $virgilCrypto->generateKeys();

        $virgilCryptoAPIMock->expects($this->once())
            ->method('publicKeyToDER')
            ->with($keys->getPublicKey()->getValue()->getData())
            ->willReturn($publicKeyDERvalue);

        $this->assertEquals(new Buffer($publicKeyDERvalue), $virgilCrypto->exportPublicKey($keys->getPublicKey()));
    }

    public function testExportPrivateKey()
    {
        $privateKeyDERvalue = 'private_key_der_value';
        $virgilCryptoAPIMock = $this->createPartialMock(VirgilCryptoApi::class, ['privateKeyToDER']);

        $virgilCrypto = new VirgilCrypto($virgilCryptoAPIMock);

        $keys = $virgilCrypto->generateKeys();

        $virgilCryptoAPIMock->expects($this->once())
            ->method('privateKeyToDER')
            ->with($keys->getPrivateKey()->getValue()->getData())
            ->willReturn($privateKeyDERvalue);

        $this->assertEquals(new Buffer($privateKeyDERvalue), $virgilCrypto->exportPrivateKey($keys->getPrivateKey()));
    }

    public function testExportPrivateKeyWithPassword()
    {
        $privateKeyDERvalue = 'private_key_der_value';
        $password = 'secure_password';
        $virgilCryptoAPIMock = $this->createPartialMock(VirgilCryptoApi::class, ['privateKeyToDER']);

        $virgilCrypto = new VirgilCrypto($virgilCryptoAPIMock);

        $keys = $virgilCrypto->generateKeys();

        $virgilCryptoAPIMock->expects($this->once())
            ->method('privateKeyToDER')
            ->with($keys->getPrivateKey()->getValue()->getData(), $password)
            ->willReturn($privateKeyDERvalue);

        $this->assertEquals(new Buffer($privateKeyDERvalue), $virgilCrypto->exportPrivateKey($keys->getPrivateKey(), $password));
    }

    public function testImportPrivateKey()
    {
        $virgilCryptoAPIMock = $this->createPartialMock(VirgilCryptoApi::class, ['computeHash', 'extractPublicKey']);

        $virgilCrypto = new VirgilCrypto($virgilCryptoAPIMock);

        $keys = $virgilCrypto->generateKeys();

        $exportedKey = $virgilCrypto->exportPrivateKey($keys->getPrivateKey());

        $expectedPrivateKey = new VirgilPrivateKey(
            'fingerprint_content_hash',
            $exportedKey->getData()
        );

        $virgilCryptoAPIMock->expects($this->once())
            ->method('computeHash')
            ->with('extracted_public_key', HashAlgorithm::SHA256)
            ->willReturn('fingerprint_content_hash');

        $virgilCryptoAPIMock->expects($this->once())
            ->method('extractPublicKey')
            ->with($exportedKey->getData(), '')
            ->willReturn('extracted_public_key');

        $this->assertEquals($expectedPrivateKey, $virgilCrypto->importPrivateKey($exportedKey));
    }

    public function testImportPrivateKeyWithPassword()
    {
        $password = 'secure_password';

        $virgilCryptoAPIMock = $this->createPartialMock(VirgilCryptoApi::class, ['computeHash', 'extractPublicKey']);

        $virgilCrypto = new VirgilCrypto($virgilCryptoAPIMock);

        $keys = $virgilCrypto->generateKeys();

        $exportedKey = $virgilCrypto->exportPrivateKey($keys->getPrivateKey(), $password);

        $expectedPrivateKey = new VirgilPrivateKey(
            'fingerprint_content_hash',
            $keys->getPrivateKey()->getValue()->getData()
        );

        $virgilCryptoAPIMock->expects($this->once())
            ->method('computeHash')
            ->with('extracted_public_key', HashAlgorithm::SHA256)
            ->willReturn('fingerprint_content_hash');

        $virgilCryptoAPIMock->expects($this->once())
            ->method('extractPublicKey')
            ->with($keys->getPrivateKey()->getValue()->getData(), '')
            ->willReturn('extracted_public_key');

        $this->assertEquals($expectedPrivateKey, $virgilCrypto->importPrivateKey($exportedKey, $password));
    }

    public function testImportPublicKey()
    {
        $virgilCryptoAPIMock = $this->createPartialMock(VirgilCryptoApi::class, ['computeHash']);

        $virgilCrypto = new VirgilCrypto($virgilCryptoAPIMock);

        $keys = $virgilCrypto->generateKeys();

        $exportedKey = $virgilCrypto->exportPublicKey($keys->getPublicKey());

        $expectedPublicKey = new VirgilPublicKey(
            'fingerprint_content_hash',
            $exportedKey->getData()
        );

        $virgilCryptoAPIMock->expects($this->once())
            ->method('computeHash')
            ->with($exportedKey->getData(), HashAlgorithm::SHA256)
            ->willReturn('fingerprint_content_hash');

        $this->assertEquals($expectedPublicKey, $virgilCrypto->importPublicKey($exportedKey));
    }
}