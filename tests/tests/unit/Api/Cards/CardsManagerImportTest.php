<?php
namespace Virgil\Sdk\Tests\Unit\Api\Cards;


use Virgil\Sdk\Api\Cards\CardsManager;

use Virgil\Sdk\Client\Card;
use Virgil\Sdk\Client\Card\CardSerializerInterface;

use Virgil\Sdk\Tests\Unit\Api\AbstractVirgilApiContextTest;

class CardsManagerImportTest extends AbstractVirgilApiContextTest
{
    /**
     * @test
     */
    public function import__exportedVirgilCard__callsCardSerializer()
    {
        $cardSerializer = $this->createCardSerizlier();
        $card = $this->createCard();

        $cardsManager = new CardsManager($this->virgilApiContext);

        $cardSerializer->expects($this->once())
                       ->method('unserialize')
                       ->with('exported virgil card')
                       ->willReturn($card)
        ;

        $cardsManager->setCardSerializer($cardSerializer);


        $cardsManager->import('exported virgil card');


        //expected one call to serializer
    }


    /**
     * @return CardSerializerInterface
     */
    protected function createCardSerizlier()
    {
        return $this->createMock(CardSerializerInterface::class);
    }


    /**
     *
     * @return Card
     */
    protected function createCard()
    {
        return $this->createMock(Card::class);
    }
}
