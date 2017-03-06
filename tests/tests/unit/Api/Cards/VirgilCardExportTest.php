<?php
namespace Virgil\Sdk\Tests\Unit\Api\Cards;


use Virgil\Sdk\Api\Cards\VirgilCard;

use Virgil\Sdk\Client\Card;
use Virgil\Sdk\Client\Card\CardSerializerInterface;

use Virgil\Sdk\Tests\Unit\Api\AbstractVirgilApiContextTest;

class VirgilCardExportTest extends AbstractVirgilApiContextTest
{
    /**
     * @test
     */
    public function export__virgilCard__callsCardSerializer()
    {
        $card = $this->createCard();
        $cardSerializer = $this->createCardSerizlier();

        $virgilCard = new VirgilCard($this->virgilApiContext, $card);

        $cardSerializer->expects($this->once())
                       ->method('serialize')
                       ->with($card)
        ;

        $virgilCard->setCardSerializer($cardSerializer);


        $virgilCard->export();


        //expected one call to serializer
    }


    /**
     *
     * @return Card
     */
    protected function createCard()
    {
        return $this->createMock(Card::class);
    }


    /**
     * @return CardSerializerInterface
     */
    protected function createCardSerizlier()
    {
        return $this->createMock(CardSerializerInterface::class);
    }
}
