<?php
namespace Virgil\Sdk\Tests\Unit\Cryptography;


use InvalidArgumentException;
use Virgil\Sdk\Tests\BaseTestCase;

use Virgil\Sdk\Buffer;

use Virgil\Sdk\Cryptography\VirgilCrypto;
use Virgil\Sdk\Cryptography\VirgilKeyPair;
use Virgil\Sdk\Cryptography\PrivateKeyReference;
use Virgil\Sdk\Cryptography\PublicKeyReference;

use Virgil\Sdk\Cryptography\Core\VirgilCryptoService;
use Virgil\Sdk\Cryptography\Core\VirgilKeyPair as CoreVirgilKeyPair;

use Virgil\Sdk\Cryptography\Constants\HashAlgorithms;
use Virgil\Sdk\Cryptography\Constants\KeyPairTypes;

class VirgilCryptoTest extends BaseTestCase
{

    /**
     * @test
     */
    public function generateKeys__withDefaultCryptoType__returnsValidKeyPair()
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

        $virgilCrypto = new VirgilCrypto();
        $virgilCrypto->setCryptoService($cryptoServiceMock);


        $actualKeys = $virgilCrypto->generateKeys();


        $this->assertEquals($expectedKeys, $actualKeys);
        $this->assertEquals(new Buffer($publicKey), $virgilCrypto->exportPublicKey($actualKeys->getPublicKey()));
        $this->assertEquals(new Buffer($privateKey), $virgilCrypto->exportPrivateKey($actualKeys->getPrivateKey()));
    }


    /**
     * @test
     */
    public function encryptThenDecrypt__withValidKeys__returnsValidResult()
    {
        $data = new Buffer('data_to_encrypt');
        $virgilCrypto = new VirgilCrypto();

        $aliceKeyPair = $virgilCrypto->generateKeys();
        $bobKeyPair = $virgilCrypto->generateKeys();


        $encryptedData = $virgilCrypto->encrypt(
            $data->getData(),
            [$aliceKeyPair->getPublicKey(), $bobKeyPair->getPublicKey()]
        );
        $decryptedDataByAlice = $virgilCrypto->decrypt($encryptedData, $aliceKeyPair->getPrivateKey());
        $decryptedDataByBob = $virgilCrypto->decrypt($encryptedData, $bobKeyPair->getPrivateKey());


        $this->assertEquals($data, $decryptedDataByAlice);
        $this->assertEquals($data, $decryptedDataByBob);
    }


    /**
     * @test
     */
    public function encryptThenDecryptStream__withValidKeys__returnsValidResult()
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


    /**
     * @test
     */
    public function calculateFingerprint__forGivenContent__returnsValidFingerprint()
    {
        $content = 'fingerprint_content';
        $expectedFingerprint = new Buffer('fingerprint_content_hash');

        $cryptoServiceMock = $this->createMock(VirgilCryptoService::class);

        $cryptoServiceMock->expects($this->once())
                          ->method('computeHash')
                          ->with($content, HashAlgorithms::SHA256)
                          ->willReturn('fingerprint_content_hash')
        ;

        $virgilCrypto = new VirgilCrypto();
        $virgilCrypto->setCryptoService($cryptoServiceMock);


        $fingerprint = $virgilCrypto->calculateFingerprint($content);


        $this->assertEquals($expectedFingerprint, $fingerprint);
    }


    /**
     * @test
     */
    public function signThenVerify__withContent__returnsValidResult()
    {
        $content = 'data_to_sign';

        $cryptoServiceMock = $this->createPartialMock(VirgilCryptoService::class, ['sign', 'verify']);
        $virgilCrypto = new VirgilCrypto();
        $virgilCrypto->setCryptoService($cryptoServiceMock);

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
        $isValid = $virgilCrypto->verify($content, $signature, $publicKeyReference);


        $this->assertTrue($isValid);
    }


    /**
     * @test
     */
    public function signThenVerifyStream__withContent__returnsValidResult()
    {
        $content = 'data_to_encrypt';
        $source = fopen('php://memory', 'r');
        fwrite($source, $content);

        $cryptoServiceMock = $this->createPartialMock(VirgilCryptoService::class, ['signStream', 'verifyStream']);
        $virgilCrypto = new VirgilCrypto();

        $virgilCrypto->setCryptoService($cryptoServiceMock);

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
        $isValid = $virgilCrypto->verifyStream($source, $signature, $publicKeyReference);


        $this->assertTrue($isValid);
    }


    /**
     * @test
     */
    public function extractPublicKey__fromPrivateKey__returnsValidPublicKey()
    {
        $virgilCrypto = new VirgilCrypto();

        $keys = $virgilCrypto->generateKeys();


        $extractedPublicKey = $virgilCrypto->extractPublicKey($keys->getPrivateKey());


        $this->assertEquals($keys->getPublicKey(), $extractedPublicKey);
    }


    /**
     * @test
     */
    public function importThenExportPrivateKey__withPrivateKeys__returnsValidResult()
    {
        $cryptoServiceMock = $this->createPartialMock(
            VirgilCryptoService::class,
            ['computeHash', 'extractPublicKey', 'privateKeyToDER']
        );

        $virgilCrypto = new VirgilCrypto();
        $virgilCrypto->setCryptoService($cryptoServiceMock);

        $expectedPrivateKey = Buffer::fromBase64('MC4CAQAwBQYDK2VwBCIEIIZcCzLErF1EscqmXnBauI5GSIcIisbEmGwp+R9MRWW+');

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
                          ->with($expectedPrivateKey->getData(), '')
                          ->willReturn('extracted_public_key')
        ;

        $cryptoServiceMock->expects($this->exactly(3))
                          ->method('privateKeyToDER')
                          ->with($expectedPrivateKey->getData())
                          ->willReturn($expectedPrivateKey->getData())
        ;


        $importedKeyReference = $virgilCrypto->importPrivateKey($expectedPrivateKey);
        $exportedPrivateKey = $virgilCrypto->exportPrivateKey($importedKeyReference);


        $this->assertEquals($expectedPrivateKey, $exportedPrivateKey);
    }


    /**
     * @test
     */
    public function importThenExportPrivateKey__withPrivateKeysAndPassword__returnsValidResult()
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

        $virgilCrypto = new VirgilCrypto();
        $virgilCrypto->setCryptoService($cryptoServiceMock);

        $exportedKeyWithPassword = Buffer::fromBase64(
            'MIGhMF0GCSqGSIb3DQEFDTBQMC8GCSqGSIb3DQEFDDAiBBCz/65j81rtPqETLglLsfNkAgIQ7jAKBggqhkiG9w0CCjAdBglghkgBZQMEASoEEMNHmKo5iiy8rHpTDcx2gGMEQAbMHw2wKtL+1Ie1Ij7Ar/52o+bnVCzyXPjfxh91V0eN0Z4mn6NfiNwyYq8HI+khp/xvRYMLQWUTOrgvGhGJ/yk='
        );
        $expectedPrivateKey = Buffer::fromBase64('MC4CAQAwBQYDK2VwBCIEIIZcCzLErF1EscqmXnBauI5GSIcIisbEmGwp+R9MRWW+');

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
                          ->with($expectedPrivateKey->getData(), '')
                          ->willReturn('extracted_public_key')
        ;

        $cryptoServiceMock->expects($this->exactly(2))
                          ->method('privateKeyToDER')
                          ->with($expectedPrivateKey->getData())
                          ->willReturn($expectedPrivateKey->getData())
        ;

        $cryptoServiceMock->expects($this->exactly(1))
                          ->method('decryptPrivateKey')
                          ->with(
                              $exportedKeyWithPassword->getData(),
                              $password
                          )
                          ->willReturn($expectedPrivateKey->getData())
        ;


        $importedKeyReference = $virgilCrypto->importPrivateKey($exportedKeyWithPassword, $password);
        $exportedPrivateKey = $virgilCrypto->exportPrivateKey($importedKeyReference);


        $this->assertEquals($expectedPrivateKey, $exportedPrivateKey);
    }


    /**
     * @test
     */
    public function importThenExportPublicKey__withPublicKeysAndPassword__returnsValidResult()
    {
        $cryptoServiceMock = $this->createPartialMock(VirgilCryptoService::class, ['computeHash', 'publicKeyToDER']);

        $virgilCrypto = new VirgilCrypto();
        $virgilCrypto->setCryptoService($cryptoServiceMock);

        $expectedPublicKey = Buffer::fromBase64('MCowBQYDK2VwAyEA9cZXjjONZguBy94+59RMQ1xSIE9es2cbCGLsNFM8yls=');

        $cryptoServiceMock->expects($this->once())
                          ->method('computeHash')
                          ->with(
                              $expectedPublicKey->getData(),
                              HashAlgorithms::SHA256
                          )
                          ->willReturn('fingerprint_content_hash')
        ;

        $cryptoServiceMock->expects($this->exactly(2))
                          ->method('publicKeyToDER')
                          ->with($expectedPublicKey->getData())
                          ->willReturn($expectedPublicKey->getData())
        ;


        $keyReference = $virgilCrypto->importPublicKey($expectedPublicKey);
        $exportedPublicKey = $virgilCrypto->exportPublicKey($keyReference);


        $this->assertEquals($expectedPublicKey, $exportedPublicKey);
    }


    /**
     * @test
     */
    public function decryptThenVerify__withKeyPairAndCipherData__returnsOriginalData()
    {
        $content = 'data_to_encrypt';
        $virgilCrypto = new VirgilCrypto();
        $aliceKeys = $virgilCrypto->generateKeys();
        $bobKeys = $virgilCrypto->generateKeys();


        $cipherData = $virgilCrypto->signThenEncrypt($content, $aliceKeys->getPrivateKey(), [$bobKeys->getPublicKey()]);
        $decryptedContent = $virgilCrypto->decryptThenVerify(
            $cipherData,
            $bobKeys->getPrivateKey(),
            $aliceKeys->getPublicKey()
        );


        $this->assertEquals(new Buffer($content), $decryptedContent);
    }


    /**
     * @test
     */
    public function sign__withNotImportedKey__throwsException()
    {
        $expectedException = InvalidArgumentException::class;
        $virgilCrypto = new VirgilCrypto();


        $testCode = function () use ($virgilCrypto) {
            $virgilCrypto->sign('data_to_sign', new PrivateKeyReference('not_existed_key_hash'));
        };


        $exception = $this->catchException($expectedException, $testCode);
        $this->assertContains('not_existed_key_hash', $exception->getMessage());
    }
}
