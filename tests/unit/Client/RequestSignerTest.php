<?php
namespace Virgil\Tests\Unit\Client;


use PHPUnit\Framework\TestCase;

use Virgil\Sdk\Buffer;
use Virgil\Sdk\Client\CardScope;
use Virgil\Sdk\Client\CreateCardRequest;
use Virgil\Sdk\Client\RequestSigner;
use Virgil\Sdk\Cryptography\CryptoAPI\VirgilCryptoApi;
use Virgil\Sdk\Cryptography\VirgilCrypto;

class RequestSignerTest extends TestCase
{
    public function testSelfSign()
    {
        /** @var VirgilCrypto $cryptoMock */
        $cryptoMock = $this->getMockBuilder(VirgilCrypto::class)
            ->setConstructorArgs([new VirgilCryptoApi()])
            ->setMethods(['sign'])
            ->getMock();

        $keys = $cryptoMock->generateKeys();

        $request = new CreateCardRequest(
            'user', 'member', $cryptoMock->exportPublicKey($keys->getPublicKey()), CardScope::TYPE_APPLICATION
        );

        $fingerprint = $cryptoMock->calculateFingerprint(Buffer::fromBase64($request->snapshot()));

        $cryptoMock
            ->expects($this->once())
            ->method('sign')
            ->with($fingerprint->getData(), $keys->getPrivateKey())
            ->willReturn(new Buffer('card_signature'));

        $signer = new RequestSigner($cryptoMock);
        $signer->selfSign($request, $keys->getPrivateKey());

        $this->assertTrue(in_array(new Buffer('card_signature'), $request->getSignatures()));
        $this->assertArrayHasKey($fingerprint->toHex(), $request->getSignatures());
    }

    public function testAuthoritySign()
    {
        /** @var VirgilCrypto $cryptoMock */
        $cryptoMock = $this->getMockBuilder(VirgilCrypto::class)
            ->setConstructorArgs([new VirgilCryptoApi()])
            ->setMethods(['sign'])
            ->getMock();

        $keys = $cryptoMock->generateKeys();

        $request = new CreateCardRequest(
            'user', 'member', $cryptoMock->exportPublicKey($keys->getPublicKey()), CardScope::TYPE_APPLICATION
        );

        $fingerprint = $cryptoMock->calculateFingerprint(Buffer::fromBase64($request->snapshot()));

        $cryptoMock
            ->expects($this->once())
            ->method('sign')
            ->with($fingerprint->getData(), $keys->getPrivateKey())
            ->willReturn(new Buffer('card_signature'));

        $signer = new RequestSigner($cryptoMock);
        $signer->authoritySign($request, 'signature_key', $keys->getPrivateKey());

        $this->assertTrue(in_array(new Buffer('card_signature'), $request->getSignatures()));
        $this->assertArrayHasKey('signature_key', $request->getSignatures());
    }
}
