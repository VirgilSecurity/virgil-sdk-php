<?php
namespace Virgil\Sdk\Tests\Unit\Api\Cards;


class CardsManagerImportTest extends AbstractCardsManagerTest
{
    /**
     * @test
     */
    public function import__exportedVirgilCard__callsCardSerializer()
    {
        $card = $this->createCard();


        $this->cardSerializer->expects($this->once())
                             ->method('unserialize')
                             ->with('exported virgil card')
                             ->willReturn($card)
        ;


        $this->cardsManager->import('exported virgil card');


        //expected one call to serializer
    }
}
