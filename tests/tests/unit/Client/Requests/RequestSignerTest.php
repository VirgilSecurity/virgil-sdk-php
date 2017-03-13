<?php
namespace Virgil\Sdk\Tests\Unit\Client\Requests;


use Virgil\Sdk\Tests\BaseTestCase;

use Virgil\Sdk\Buffer;

use Virgil\Sdk\Client\Requests\Constants\CardScopes;

use Virgil\Sdk\Client\Requests\CreateCardRequest;
use Virgil\Sdk\Client\Requests\RequestSigner;

use Virgil\Sdk\Cryptography\Core\VirgilCryptoService;

use Virgil\Sdk\Cryptography\VirgilCrypto;

class RequestSignerTest extends BaseTestCase
{
    /**
     * @test
     */
    public function selfSign__requestWithOwnerPrivateKey__correctOwnerSignatureAndSignatureIdAppendsToRequestSignatures(
    )
    {
        /** @var VirgilCrypto $cryptoMock */
        $cryptoMock = $this->getMockBuilder(VirgilCrypto::class)
                           ->setMethods(['sign'])
                           ->getMock()
        ;

        $keys = $cryptoMock->generateKeys();

        $request = new CreateCardRequest(
            'user', 'member', $cryptoMock->exportPublicKey($keys->getPublicKey()), CardScopes::TYPE_APPLICATION
        );

        $fingerprint = $cryptoMock->calculateFingerprint(base64_decode($request->getSnapshot()));

        $cryptoMock->expects($this->once())
                   ->method('sign')
                   ->with($fingerprint->getData(), $keys->getPrivateKey())
                   ->willReturn(new Buffer('card_signature'))
        ;


        $signer = new RequestSigner($cryptoMock);
        $signer->selfSign($request, $keys->getPrivateKey());


        $this->assertTrue(in_array(new Buffer('card_signature'), $request->getSignatures()));
        $this->assertArrayHasKey($fingerprint->toHex(), $request->getSignatures());
    }


    /**
     * @test
     */
    public function authoritySign__requestWithAuthorityPrivateKeyAndAppId__correctAuthoritySignatureAndSignatureIdAppendsToRequestSignatures(
    )
    {
        /** @var VirgilCrypto $cryptoMock */
        $cryptoMock = $this->getMockBuilder(VirgilCrypto::class)
                           ->setMethods(['sign'])
                           ->getMock()
        ;

        $keys = $cryptoMock->generateKeys();

        $request = new CreateCardRequest(
            'user', 'member', $cryptoMock->exportPublicKey($keys->getPublicKey()), CardScopes::TYPE_APPLICATION
        );

        $fingerprint = $cryptoMock->calculateFingerprint(base64_decode($request->getSnapshot()));

        $cryptoMock->expects($this->once())
                   ->method('sign')
                   ->with($fingerprint->getData(), $keys->getPrivateKey())
                   ->willReturn(new Buffer('card_signature'))
        ;


        $signer = new RequestSigner($cryptoMock);
        $signer->authoritySign($request, 'signature_key', $keys->getPrivateKey());


        $this->assertTrue(in_array(new Buffer('card_signature'), $request->getSignatures()));
        $this->assertArrayHasKey('signature_key', $request->getSignatures());
    }
}
