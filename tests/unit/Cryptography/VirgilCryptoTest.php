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
        $publicKeyHash = new Buffer('public_key_hash');
        $privateKeyHash = new Buffer('private_key_hash');

        $expectedKeys = new VirgilKeyPair(
            new VirgilPublicKey($publicKeyHash->toHex()), new VirgilPrivateKey($privateKeyHash->toHex())
        );

        $virgilKeyPair = new CryptoAPIVirgilKeyPair($publicKey, $privateKey);

        $virgilCryptoAPIMock = $this->createPartialMock(VirgilCryptoApi::class,
            ['generate', 'publicKeyToDER', 'privateKeyToDER', 'computeHash']);

        $virgilCryptoAPIMock->expects($this->once())
            ->method('generate')
            ->with(KeyPairType::DefaultType)
            ->willReturn($virgilKeyPair);

        $virgilCryptoAPIMock->expects($this->exactly(2))
            ->method('publicKeyToDER')
            ->with($publicKey)
            ->willReturn($publicKey);

        $virgilCryptoAPIMock->expects($this->exactly(2))
            ->method('privateKeyToDER')
            ->with($privateKey, '')
            ->willReturn($privateKey);

        $virgilCryptoAPIMock->expects($this->exactly(2))
            ->method('computeHash')
            ->will($this->returnValueMap(
                [
                    [$privateKey, HashAlgorithm::DefaultType, $privateKeyHash->getData()],
                    [$publicKey, HashAlgorithm::DefaultType, $publicKeyHash->getData()]
                ]
            ));

        $virgilCrypto = new VirgilCrypto($virgilCryptoAPIMock);

        $actualKeys = $virgilCrypto->generateKeys();

        $this->assertEquals($expectedKeys, $actualKeys);
        $this->assertEquals(new Buffer($publicKey), $virgilCrypto->exportPublicKey($actualKeys->getPublicKey()));
        $this->assertEquals(new Buffer($privateKey), $virgilCrypto->exportPrivateKey($actualKeys->getPrivateKey()));
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

        $privateKey = Buffer::fromBase64('MC4CAQAwBQYDK2VwBCIEIB4bj3f9XEvvM6Z8F42oJr7nHpuBEIxm42Y2CqPtCng5');
        $publicKey = Buffer::fromBase64('MCowBQYDK2VwAyEAX9FREHNOfQ7b1W9b+iSc2rdMhTrZ/HxmHvMuhYiRd9g=');

        $virgilCryptoAPIMock->expects($this->once())
            ->method('sign')
            ->with($content, $privateKey->getData())
            ->willReturn('data_signature');

        $virgilCryptoAPIMock->expects($this->once())
            ->method('verify')
            ->with($content, 'data_signature', $publicKey->getData())
            ->willReturn(true);

        $privateKeyReference = $virgilCrypto->importPrivateKey($privateKey);
        $publicKeyReference = $virgilCrypto->importPublicKey($publicKey);

        $signature = $virgilCrypto->sign($content, $privateKeyReference);
        $this->assertTrue($virgilCrypto->verify($content, $signature, $publicKeyReference));
    }

    public function testStreamSignVerify()
    {
        $content = 'data_to_encrypt';
        $source = fopen('php://memory', 'r');
        fwrite($source, $content);

        $virgilCryptoAPIMock = $this->createPartialMock(VirgilCryptoApi::class, ['streamSign', 'streamVerify']);
        $virgilCrypto = new VirgilCrypto($virgilCryptoAPIMock);

        $privateKey = Buffer::fromBase64('MC4CAQAwBQYDK2VwBCIEIB4bj3f9XEvvM6Z8F42oJr7nHpuBEIxm42Y2CqPtCng5');
        $publicKey = Buffer::fromBase64('MCowBQYDK2VwAyEAX9FREHNOfQ7b1W9b+iSc2rdMhTrZ/HxmHvMuhYiRd9g=');

        $virgilCryptoAPIMock->expects($this->once())
            ->method('streamSign')
            ->with($source, $privateKey->getData())
            ->willReturn('data_signature');

        $virgilCryptoAPIMock->expects($this->once())
            ->method('streamVerify')
            ->with($source, 'data_signature', $publicKey->getData())
            ->willReturn(true);

        $privateKeyReference = $virgilCrypto->importPrivateKey($privateKey);
        $publicKeyReference = $virgilCrypto->importPublicKey($publicKey);

        $signature = $virgilCrypto->streamSign($source, $privateKeyReference);
        $this->assertTrue($virgilCrypto->streamVerify($source, $signature, $publicKeyReference));
    }

    public function testExtractPublicKeyFromPrivateOne()
    {
        $virgilCrypto = new VirgilCrypto();

        $keys = $virgilCrypto->generateKeys();

        $this->assertEquals(
            $keys->getPublicKey(),
            $virgilCrypto->extractPublicKey($keys->getPrivateKey())
        );
    }

    public function testImportExportPrivateKey()
    {
        $virgilCryptoAPIMock = $this->createPartialMock(VirgilCryptoApi::class, ['computeHash', 'extractPublicKey', 'privateKeyToDER']);

        $virgilCrypto = new VirgilCrypto($virgilCryptoAPIMock);

        $exportedKey = Buffer::fromBase64('MC4CAQAwBQYDK2VwBCIEIIZcCzLErF1EscqmXnBauI5GSIcIisbEmGwp+R9MRWW+');

        $virgilCryptoAPIMock->expects($this->exactly(2))
            ->method('computeHash')
            ->with($this->anything(), HashAlgorithm::SHA256)
            ->willReturn('fingerprint_content_hash');

        $virgilCryptoAPIMock->expects($this->once())
            ->method('extractPublicKey')
            ->with($exportedKey->getData(), '')
            ->willReturn('extracted_public_key');

        $virgilCryptoAPIMock->expects($this->exactly(3))
            ->method('privateKeyToDER')
            ->with($exportedKey->getData())
            ->willReturn($exportedKey->getData());

        $importedKeyReference = $virgilCrypto->importPrivateKey($exportedKey);

        $this->assertEquals($exportedKey, $virgilCrypto->exportPrivateKey($importedKeyReference));
    }

    public function testImportExportPrivateKeyWithPassword()
    {
        $password = 'secure_password';

        $virgilCryptoAPIMock = $this->createPartialMock(VirgilCryptoApi::class, ['computeHash', 'extractPublicKey', 'privateKeyToDER', 'decryptPrivateKey']);

        $virgilCrypto = new VirgilCrypto($virgilCryptoAPIMock);

        $exportedKeyWithPassword = Buffer::fromBase64('MIGhMF0GCSqGSIb3DQEFDTBQMC8GCSqGSIb3DQEFDDAiBBCz/65j81rtPqETLglLsfNkAgIQ7jAKBggqhkiG9w0CCjAdBglghkgBZQMEASoEEMNHmKo5iiy8rHpTDcx2gGMEQAbMHw2wKtL+1Ie1Ij7Ar/52o+bnVCzyXPjfxh91V0eN0Z4mn6NfiNwyYq8HI+khp/xvRYMLQWUTOrgvGhGJ/yk=');
        $exportedKey = Buffer::fromBase64('MC4CAQAwBQYDK2VwBCIEIIZcCzLErF1EscqmXnBauI5GSIcIisbEmGwp+R9MRWW+');

        $virgilCryptoAPIMock->expects($this->exactly(2))
            ->method('computeHash')
            ->with($this->anything(), $this->anything())
            ->willReturn('fingerprint_content_hash');

        $virgilCryptoAPIMock->expects($this->once())
            ->method('extractPublicKey')
            ->with($exportedKey->getData(), '')
            ->willReturn('extracted_public_key');

        $virgilCryptoAPIMock->expects($this->exactly(2))
            ->method('privateKeyToDER')
            ->with($exportedKey->getData())
            ->willReturn($exportedKey->getData());

        $virgilCryptoAPIMock->expects($this->exactly(1))
            ->method('decryptPrivateKey')
            ->with($exportedKeyWithPassword->getData(), $password)
            ->willReturn($exportedKey->getData());

        $importedKeyReference = $virgilCrypto->importPrivateKey($exportedKeyWithPassword, $password);

        $this->assertEquals($exportedKey, $virgilCrypto->exportPrivateKey($importedKeyReference));
    }

    public function testImportExportPublicKey()
    {
        $virgilCryptoAPIMock = $this->createPartialMock(VirgilCryptoApi::class, ['computeHash', 'publicKeyToDER']);

        $virgilCrypto = new VirgilCrypto($virgilCryptoAPIMock);

        $exportedKey = Buffer::fromBase64('MCowBQYDK2VwAyEA9cZXjjONZguBy94+59RMQ1xSIE9es2cbCGLsNFM8yls=');

        $virgilCryptoAPIMock->expects($this->once())
            ->method('computeHash')
            ->with($exportedKey->getData(), HashAlgorithm::SHA256)
            ->willReturn('fingerprint_content_hash');

        $virgilCryptoAPIMock->expects($this->exactly(2))
            ->method('publicKeyToDER')
            ->with($exportedKey->getData())
            ->willReturn($exportedKey->getData());

        $keyReference = $virgilCrypto->importPublicKey($exportedKey);

        $this->assertEquals($exportedKey, $virgilCrypto->exportPublicKey($keyReference));
    }

    public function testDataEncryptionDecryptionWithSignAndVerifyAtOnce()
    {
        $content = 'data_to_encrypt';
        $virgilCrypto = new VirgilCrypto();
        $aliceKeys = $virgilCrypto->generateKeys();
        $bobKeys = $virgilCrypto->generateKeys();
        $cipherData = $virgilCrypto->signThenEncrypt($content, $aliceKeys->getPrivateKey(), [$bobKeys->getPublicKey()]);

        $this->assertEquals(new Buffer($content), $virgilCrypto->decryptThenVerify($cipherData, $bobKeys->getPrivateKey(), $aliceKeys->getPublicKey()));
    }

    public function testSignWithNotImportedKeyThrowsException()
    {
        $virgilCrypto = new VirgilCrypto();
        try {
            $virgilCrypto->sign('data_to_sign', new VirgilPrivateKey('not_existed_key_hash'));
        } catch (\InvalidArgumentException $exception) {
            $this->assertContains('not_existed_key_hash', $exception->getMessage());
        }
    }
}