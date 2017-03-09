<?php
namespace Virgil\Sdk\Tests\Unit\Api\Cards;


class VirgilCardExportTest extends AbstractVirgilCardTest
{
    /**
     * @test
     */
    public function export__virgilCard__callsCardSerializer()
    {
        $aliceCard = $this->createCard();

        $aliceVirgilCard = $this->createVirgilCard($aliceCard);

        $this->cardSerializer->expects($this->once())
                             ->method('serialize')
                             ->with($aliceCard)
        ;


        $aliceVirgilCard->export();


        //expected one call to serializer
    }
}
