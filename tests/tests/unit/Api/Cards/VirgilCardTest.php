<?php
namespace Virgil\Sdk\Tests\Unit\Api\Cards;


use Virgil\Sdk\Buffer;

use Virgil\Sdk\Client\Card;

use Virgil\Sdk\Contracts\BufferInterface;
use Virgil\Sdk\Contracts\PublicKeyInterface;

use Virgil\Sdk\Api\Cards\VirgilCard;

use Virgil\Sdk\Tests\Unit\Api\AbstractVirgilApiContextTest;

class VirgilCardTest extends AbstractVirgilApiContextTest
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
        $aliceCard = $this->createCard(new Buffer('alice-public-key'));
        $alicePublicKey = $this->createMock(PublicKeyInterface::class);

        $this->crypto->expects($this->once())
                     ->method('importPublicKey')
                     ->with($aliceCard->getPublicKeyData())
                     ->willReturn($alicePublicKey)
        ;


        $this->crypto->expects($this->once())
                     ->method('encrypt')
                     ->with($stringContentRepresentation, [$alicePublicKey])
        ;


        $aliceVirgilCard = new VirgilCard($this->virgilApiContext, $aliceCard);


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
        $aliceCard = $this->createCard(new Buffer('alice-public-key'));
        $alicePublicKey = $this->createMock(PublicKeyInterface::class);

        $this->crypto->expects($this->once())
                     ->method('importPublicKey')
                     ->with($aliceCard->getPublicKeyData())
                     ->willReturn($alicePublicKey)
        ;

        $this->crypto->expects($this->once())
                     ->method('verify')
                     ->with($stringContentRepresentation, $signature, $alicePublicKey)
        ;


        $aliceVirgilCard = new VirgilCard($this->virgilApiContext, $aliceCard);


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
        $aliceCard = $this->createCard(new Buffer('alice-public-key'));
        $alicePublicKey = $this->createMock(PublicKeyInterface::class);

        $this->crypto->expects($this->once())
                     ->method('importPublicKey')
                     ->with($aliceCard->getPublicKeyData())
                     ->willReturn($alicePublicKey)
        ;

        $this->crypto->expects($this->once())
                     ->method('verify')
                     ->with($stringContentRepresentation, $signature, $alicePublicKey)
        ;


        $aliceVirgilCard = new VirgilCard($this->virgilApiContext, $aliceCard);


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


    /**
     * @param $publicKeyData
     *
     * @return Card
     */
    protected function createCard($publicKeyData)
    {
        $cardMock = $this->createMock(Card::class);

        $cardMock->expects($this->any())
                 ->method('getPublicKeyData')
                 ->willReturn($publicKeyData)
        ;

        return $cardMock;
    }
}
