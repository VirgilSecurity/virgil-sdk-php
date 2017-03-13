<?php
namespace Virgil\Sdk\Tests\Unit\Api\Cards;


use Virgil\Sdk\Buffer;

use Virgil\Sdk\Api\Cards\VirgilCard;

use Virgil\Sdk\Contracts\PublicKeyInterface;

class VirgilCardsTest extends AbstractVirgilCardsTest
{
    /**
     * @dataProvider encryptContentProvider
     *
     * @param $content
     * @param $stringContentRepresentation
     *
     * @test
     */
    public function encrypt__withMixedTypeContent__callsVirgilCryptoEncryptWithStringContentRepresentation(
        $content,
        $stringContentRepresentation
    ) {
        $bobVirgilCard = $this->createVirgilCard();
        $aliceVirgilCard = $this->createVirgilCard();

        $alicePublicKey = $this->createMock(PublicKeyInterface::class);
        $bobPublicKey = $this->createMock(PublicKeyInterface::class);


        $bobVirgilCard->expects($this->once())
                      ->method('getPublicKey')
                      ->willReturn($bobPublicKey)
        ;

        $aliceVirgilCard->expects($this->once())
                        ->method('getPublicKey')
                        ->willReturn($alicePublicKey)
        ;

        $this->crypto->expects($this->once())
                     ->method('encrypt')
                     ->with($stringContentRepresentation, [$alicePublicKey, $bobPublicKey])
        ;


        $virgilCards = $this->createVirgilCards([$bobVirgilCard, $aliceVirgilCard]);


        $virgilCards->encrypt($content);
    }


    public function encryptContentProvider()
    {
        return [
            [new Buffer('hello alice and bob'), 'hello alice and bob'],
            ['hello alice and bob', 'hello alice and bob'],
            [Buffer::fromHex('68656c6c6f20616c69636520616e6420626f62'), 'hello alice and bob'],
            [Buffer::fromBase64('aGVsbG8gYWxpY2UgYW5kIGJvYg=='), 'hello alice and bob'],
        ];
    }


    /**
     * @return VirgilCard
     */
    protected function createVirgilCard()
    {
        $virgilCard = $this->createMock(VirgilCard::class);

        return $virgilCard;
    }

}
