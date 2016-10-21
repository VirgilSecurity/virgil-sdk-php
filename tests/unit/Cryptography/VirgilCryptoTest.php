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
}