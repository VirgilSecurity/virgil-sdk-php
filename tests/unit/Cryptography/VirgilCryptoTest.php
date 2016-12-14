<?php
namespace Virgil\Tests\Unit\Cryptography;


use PHPUnit\Framework\TestCase;

use Virgil\Sdk\Buffer;
use Virgil\Sdk\Cryptography\Core\VirgilCryptoService;
use Virgil\Sdk\Cryptography\VirgilCrypto;
use Virgil\Sdk\Cryptography\VirgilKeyPair;
use Virgil\Sdk\Cryptography\Core\VirgilKeyPair as CoreVirgilKeyPair;
use Virgil\Sdk\Cryptography\PrivateKeyReference;
use Virgil\Sdk\Cryptography\PublicKeyReference;
use Virgil\Sdk\Cryptography\Constants\HashAlgorithms;
use Virgil\Sdk\Cryptography\Constants\KeyPairTypes;

class VirgilCryptoTest extends TestCase
{

    public function testThisShouldGenerateKeys()
    {
        $publicKey = 'public_key';
        $privateKey = 'private_key';
        $publicKeyHash = new Buffer('public_key_hash');
        $privateKeyHash = new Buffer('private_key_hash');

        $expectedKeys = new VirgilKeyPair(
            new PublicKeyReference($publicKeyHash->toHex()), new PrivateKeyReference($privateKeyHash->toHex())
        );

        $virgilKeyPair = new CoreVirgilKeyPair($publicKey, $privateKey);

        $cryptoServiceMock = $this->createPartialMock(
            VirgilCryptoService::class,
            ['generateKeyPair', 'publicKeyToDER', 'privateKeyToDER', 'computeHash']
        );

        $cryptoServiceMock->expects($this->once())
                            ->method('generateKeyPair')
                            ->with(KeyPairTypes::FAST_EC_ED25519)
                            ->willReturn($virgilKeyPair)
        ;

        $cryptoServiceMock->expects($this->exactly(2))
                            ->method('publicKeyToDER')
                            ->with($publicKey)
                            ->willReturn($publicKey)
        ;

        $cryptoServiceMock->expects($this->exactly(2))
                            ->method('privateKeyToDER')
                            ->with($privateKey, '')
                            ->willReturn($privateKey)
        ;

        $cryptoServiceMock->expects($this->exactly(2))
                            ->method('computeHash')
                            ->will(
                                $this->returnValueMap(
                                    [
                                        [$privateKey, HashAlgorithms::SHA256, $privateKeyHash->getData()],
                                        [$publicKey, HashAlgorithms::SHA256, $publicKeyHash->getData()],
                                    ]
                                )
                            )
        ;

        $virgilCrypto = new VirgilCrypto($cryptoServiceMock);

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

        $virgilCrypto->encryptStream($source, $sin, [$keys->getPublicKey(), $keys2->getPublicKey()]);
        rewind($sin);
        $this->assertNotEquals($data, stream_get_contents($sin));

        $source = fopen('php://memory', 'w');
        $virgilCrypto->decryptStream($sin, $source, $keys->getPrivateKey());
        rewind($source);
        $this->assertEquals($data, stream_get_contents($source));

        $source = fopen('php://memory', 'w');
        $virgilCrypto->decryptStream($sin, $source, $keys2->getPrivateKey());
        rewind($source);
        $this->assertEquals($data, stream_get_contents($source));
    }


    public function testCalculateFingerprint()
    {
        $content = 'fingerprint_content';
        $expectedFingerprint = new Buffer('fingerprint_content_hash');

        $cryptoServiceMock = $this->createMock(VirgilCryptoService::class);

        $cryptoServiceMock->expects($this->once())
                            ->method('computeHash')
                            ->with($content, HashAlgorithms::SHA256)
                            ->willReturn('fingerprint_content_hash')
        ;

        $virgilCrypto = new VirgilCrypto($cryptoServiceMock);
        $this->assertEquals($expectedFingerprint, $virgilCrypto->calculateFingerprint(new Buffer($content)));
    }


    public function testDataSignVerify()
    {
        $content = 'data_to_sign';

        $cryptoServiceMock = $this->createPartialMock(VirgilCryptoService::class, ['sign', 'verify']);
        $virgilCrypto = new VirgilCrypto($cryptoServiceMock);

        $privateKey = Buffer::fromBase64('MC4CAQAwBQYDK2VwBCIEIB4bj3f9XEvvM6Z8F42oJr7nHpuBEIxm42Y2CqPtCng5');
        $publicKey = Buffer::fromBase64('MCowBQYDK2VwAyEAX9FREHNOfQ7b1W9b+iSc2rdMhTrZ/HxmHvMuhYiRd9g=');

        $cryptoServiceMock->expects($this->once())
                            ->method('sign')
                            ->with($content, $privateKey->getData())
                            ->willReturn('data_signature')
        ;

        $cryptoServiceMock->expects($this->once())
                            ->method('verify')
                            ->with(
                                $content,
                                'data_signature',
                                $publicKey->getData()
                            )
                            ->willReturn(true)
        ;

        $privateKeyReference = $virgilCrypto->importPrivateKey($privateKey);
        $publicKeyReference = $virgilCrypto->importPublicKey($publicKey);

        $signature = $virgilCrypto->sign($content, $privateKeyReference);
        $this->assertTrue($virgilCrypto->verify($content, $signature, $publicKeyReference));
    }


    public function testSignStreamVerify()
    {
        $content = 'data_to_encrypt';
        $source = fopen('php://memory', 'r');
        fwrite($source, $content);

        $cryptoServiceMock = $this->createPartialMock(VirgilCryptoService::class, ['signStream', 'verifyStream']);
        $virgilCrypto = new VirgilCrypto($cryptoServiceMock);

        $privateKey = Buffer::fromBase64('MC4CAQAwBQYDK2VwBCIEIB4bj3f9XEvvM6Z8F42oJr7nHpuBEIxm42Y2CqPtCng5');
        $publicKey = Buffer::fromBase64('MCowBQYDK2VwAyEAX9FREHNOfQ7b1W9b+iSc2rdMhTrZ/HxmHvMuhYiRd9g=');

        $cryptoServiceMock->expects($this->once())
                            ->method('signStream')
                            ->with($source, $privateKey->getData())
                            ->willReturn('data_signature')
        ;

        $cryptoServiceMock->expects($this->once())
                            ->method('verifyStream')
                            ->with(
                                $source,
                                'data_signature',
                                $publicKey->getData()
                            )
                            ->willReturn(true)
        ;

        $privateKeyReference = $virgilCrypto->importPrivateKey($privateKey);
        $publicKeyReference = $virgilCrypto->importPublicKey($publicKey);

        $signature = $virgilCrypto->signStream($source, $privateKeyReference);
        $this->assertTrue($virgilCrypto->verifyStream($source, $signature, $publicKeyReference));
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
        $cryptoServiceMock = $this->createPartialMock(
            VirgilCryptoService::class,
            ['computeHash', 'extractPublicKey', 'privateKeyToDER']
        );

        $virgilCrypto = new VirgilCrypto($cryptoServiceMock);

        $exportedKey = Buffer::fromBase64('MC4CAQAwBQYDK2VwBCIEIIZcCzLErF1EscqmXnBauI5GSIcIisbEmGwp+R9MRWW+');

        $cryptoServiceMock->expects($this->exactly(2))
                            ->method('computeHash')
                            ->with(
                                $this->anything(),
                                HashAlgorithms::SHA256
                            )
                            ->willReturn('fingerprint_content_hash')
        ;

        $cryptoServiceMock->expects($this->once())
                            ->method('extractPublicKey')
                            ->with($exportedKey->getData(), '')
                            ->willReturn('extracted_public_key')
        ;

        $cryptoServiceMock->expects($this->exactly(3))
                            ->method('privateKeyToDER')
                            ->with($exportedKey->getData())
                            ->willReturn($exportedKey->getData())
        ;

        $importedKeyReference = $virgilCrypto->importPrivateKey($exportedKey);

        $this->assertEquals($exportedKey, $virgilCrypto->exportPrivateKey($importedKeyReference));
    }


    public function testImportExportPrivateKeyWithPassword()
    {
        $password = 'secure_password';

        $cryptoServiceMock = $this->createPartialMock(
            VirgilCryptoService::class,
            [
                'computeHash',
                'extractPublicKey',
                'privateKeyToDER',
                'decryptPrivateKey',
            ]
        );

        $virgilCrypto = new VirgilCrypto($cryptoServiceMock);

        $exportedKeyWithPassword = Buffer::fromBase64(
            'MIGhMF0GCSqGSIb3DQEFDTBQMC8GCSqGSIb3DQEFDDAiBBCz/65j81rtPqETLglLsfNkAgIQ7jAKBggqhkiG9w0CCjAdBglghkgBZQMEASoEEMNHmKo5iiy8rHpTDcx2gGMEQAbMHw2wKtL+1Ie1Ij7Ar/52o+bnVCzyXPjfxh91V0eN0Z4mn6NfiNwyYq8HI+khp/xvRYMLQWUTOrgvGhGJ/yk='
        );
        $exportedKey = Buffer::fromBase64('MC4CAQAwBQYDK2VwBCIEIIZcCzLErF1EscqmXnBauI5GSIcIisbEmGwp+R9MRWW+');

        $cryptoServiceMock->expects($this->exactly(2))
                            ->method('computeHash')
                            ->with(
                                $this->anything(),
                                $this->anything()
                            )
                            ->willReturn('fingerprint_content_hash')
        ;

        $cryptoServiceMock->expects($this->once())
                            ->method('extractPublicKey')
                            ->with($exportedKey->getData(), '')
                            ->willReturn('extracted_public_key')
        ;

        $cryptoServiceMock->expects($this->exactly(2))
                            ->method('privateKeyToDER')
                            ->with($exportedKey->getData())
                            ->willReturn($exportedKey->getData())
        ;

        $cryptoServiceMock->expects($this->exactly(1))
                            ->method('decryptPrivateKey')
                            ->with(
                                $exportedKeyWithPassword->getData(),
                                $password
                            )
                            ->willReturn($exportedKey->getData())
        ;

        $importedKeyReference = $virgilCrypto->importPrivateKey($exportedKeyWithPassword, $password);

        $this->assertEquals($exportedKey, $virgilCrypto->exportPrivateKey($importedKeyReference));
    }


    public function testImportExportPublicKey()
    {
        $cryptoServiceMock = $this->createPartialMock(VirgilCryptoService::class, ['computeHash', 'publicKeyToDER']);

        $virgilCrypto = new VirgilCrypto($cryptoServiceMock);

        $exportedKey = Buffer::fromBase64('MCowBQYDK2VwAyEA9cZXjjONZguBy94+59RMQ1xSIE9es2cbCGLsNFM8yls=');

        $cryptoServiceMock->expects($this->once())
                            ->method('computeHash')
                            ->with(
                                $exportedKey->getData(),
                                HashAlgorithms::SHA256
                            )
                            ->willReturn('fingerprint_content_hash')
        ;

        $cryptoServiceMock->expects($this->exactly(2))
                            ->method('publicKeyToDER')
                            ->with($exportedKey->getData())
                            ->willReturn($exportedKey->getData())
        ;

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

        $this->assertEquals(
            new Buffer($content),
            $virgilCrypto->decryptThenVerify(
                $cipherData,
                $bobKeys->getPrivateKey(),
                $aliceKeys->getPublicKey()
            )
        );
    }


    public function testSignWithNotImportedKeyThrowsException()
    {
        $virgilCrypto = new VirgilCrypto();
        try {
            $virgilCrypto->sign('data_to_sign', new PrivateKeyReference('not_existed_key_hash'));
        } catch (\InvalidArgumentException $exception) {
            $this->assertContains('not_existed_key_hash', $exception->getMessage());
        }
    }
}
