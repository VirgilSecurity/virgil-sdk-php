<?php
namespace Virgil\Sdk\Tests\Unit\Api\Cards;


use PHPUnit_Framework_MockObject_MockObject;

use Virgil\Sdk\Buffer;

use Virgil\Sdk\Contracts\BufferInterface;
use Virgil\Sdk\Contracts\PublicKeyInterface;

class VirgilCardTest extends AbstractVirgilCardTest
{
    /**
     * @dataProvider encryptContentProvider
     *
     * @param mixed  $content
     * @param string $stringContentRepresentation
     *
     * @test
     */
    public function encrypt__withMixedTypeContent__callsVirgilCryptoEncryptWithStringContentRepresentation(
        $content,
        $stringContentRepresentation
    ) {
        $aliceCard = $this->createCard();
        $alicePublicKeyData = new Buffer('alice-public-key');
        $alicePublicKey = $this->createMock(PublicKeyInterface::class);

        $this->configureGetPublicKey($aliceCard, $alicePublicKey, $alicePublicKeyData);


        $this->crypto->expects($this->once())
                     ->method('encrypt')
                     ->with($stringContentRepresentation, [$alicePublicKey])
        ;

        $aliceVirgilCard = $this->createVirgilCard($aliceCard);


        $aliceVirgilCard->encrypt($content);
    }


    /**
     * @dataProvider verifyCipherContentProvider
     *
     * @param mixed           $content
     * @param string          $stringContentRepresentation
     * @param BufferInterface $signature
     *
     * @test
     */
    public function verify__withMixedTypeContent__callsVirgilCryptoVerifyWithStringContentRepresentation(
        $content,
        $stringContentRepresentation,
        $signature
    ) {
        $aliceCard = $this->createCard();
        $alicePublicKeyData = new Buffer('alice-public-key');
        $alicePublicKey = $this->createMock(PublicKeyInterface::class);

        $this->configureGetPublicKey($aliceCard, $alicePublicKey, $alicePublicKeyData);

        $this->crypto->expects($this->once())
                     ->method('verify')
                     ->with($stringContentRepresentation, $signature, $alicePublicKey)
        ;

        $aliceVirgilCard = $this->createVirgilCard($aliceCard);


        $aliceVirgilCard->verify($content, $signature);
    }


    /**
     * @dataProvider verifyCipherContentProvider
     *
     * @param mixed           $content
     * @param string          $stringContentRepresentation
     * @param BufferInterface $signature
     *
     * @param string          $base64EncodedSignature
     *
     * @test
     */
    public function verify__withBase64EncodedSignature__callsVirgilCryptoVerifyWithBufferSignatureRepresentation(
        $content,
        $stringContentRepresentation,
        $signature,
        $base64EncodedSignature
    ) {
        $aliceCard = $this->createCard();
        $alicePublicKeyData = new Buffer('alice-public-key');
        $alicePublicKey = $this->createMock(PublicKeyInterface::class);

        $this->configureGetPublicKey($aliceCard, $alicePublicKey, $alicePublicKeyData);

        $this->crypto->expects($this->once())
                     ->method('verify')
                     ->with($stringContentRepresentation, $signature, $alicePublicKey)
        ;

        $aliceVirgilCard = $this->createVirgilCard($aliceCard);


        $aliceVirgilCard->verify($stringContentRepresentation, $base64EncodedSignature);
    }


    public function verifyCipherContentProvider()
    {
        return [
            [new Buffer('encrypted content'), 'encrypted content', new Buffer('sign'), 'c2lnbg=='],
            ['encrypted content', 'encrypted content', new Buffer('sign'), 'c2lnbg=='],
            [
                Buffer::fromHex('656e6372797074656420636f6e74656e74'),
                'encrypted content',
                new Buffer('sign'),
                'c2lnbg==',
            ],
            [Buffer::fromBase64('ZW5jcnlwdGVkIGNvbnRlbnQ='), 'encrypted content', new Buffer('sign'), 'c2lnbg=='],
        ];
    }


    public function encryptContentProvider()
    {
        return [
            [new Buffer('encrypt me'), 'encrypt me'],
            ['encrypt me', 'encrypt me'],
            [Buffer::fromHex('656e6372797074206d65'), 'encrypt me'],
            [Buffer::fromBase64('ZW5jcnlwdCBtZQ=='), 'encrypt me'],
        ];
    }


    protected function configureGetPublicKey(
        PHPUnit_Framework_MockObject_MockObject $aliceCard,
        $publicKey,
        BufferInterface $publicKeyData
    ) {
        $aliceCard->expects($this->any())
                  ->method('getPublicKeyData')
                  ->willReturn($publicKeyData)
        ;

        $this->crypto->expects($this->once())
                     ->method('importPublicKey')
                     ->with($publicKeyData)
                     ->willReturn($publicKey)
        ;
    }
}
