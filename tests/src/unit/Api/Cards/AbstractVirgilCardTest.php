<?php
namespace Virgil\Sdk\Tests\Unit\Api\Cards;


use PHPUnit_Framework_MockObject_MockObject;

use Virgil\Sdk\Api\Cards\VirgilCard;
use Virgil\Sdk\Api\Cards\VirgilCardInterface;

use Virgil\Sdk\Client\Card;
use Virgil\Sdk\Client\Card\CardSerializerInterface;
use Virgil\Sdk\Client\VirgilClientInterface;

use Virgil\Sdk\Contracts\CryptoInterface;

use Virgil\Sdk\Tests\BaseTestCase;

class AbstractVirgilCardTest extends BaseTestCase
{
    /** @var PHPUnit_Framework_MockObject_MockObject */
    protected $virgilClient;

    /** @var PHPUnit_Framework_MockObject_MockObject */
    protected $crypto;

    /** @var PHPUnit_Framework_MockObject_MockObject */
    protected $cardSerializer;


    public function setUp()
    {
        $this->virgilClient = $this->createVirgilClient();
        $this->crypto = $this->createCrypto();
        $this->cardSerializer = $this->createCardSerializer();
    }


    /**
     * @param VirgilClientInterface   $virgilClient
     * @param CryptoInterface         $crypto
     * @param CardSerializerInterface $cardSerializer
     * @param Card                    $card
     *
     * @return VirgilCardInterface
     */
    protected function getVirgilCard(
        VirgilClientInterface $virgilClient,
        CryptoInterface $crypto,
        CardSerializerInterface $cardSerializer,
        Card $card
    ) {
        return new VirgilCard(
            $crypto, $virgilClient, $cardSerializer, $card
        );
    }


    /**
     * @param Card $card
     *
     * @return VirgilCardInterface
     */
    protected function createVirgilCard(Card $card)
    {
        return $this->getVirgilCard(
            $this->virgilClient,
            $this->crypto,
            $this->cardSerializer,
            $card
        );
    }


    /**
     * @return CardSerializerInterface
     */
    protected function createCardSerializer()
    {
        return $this->createMock(CardSerializerInterface::class);
    }


    /**
     * @return VirgilClientInterface
     */
    protected function createVirgilClient()
    {
        return $this->createMock(VirgilClientInterface::class);
    }


    /**
     * @return CryptoInterface
     */
    protected function createCrypto()
    {
        return $this->createMock(CryptoInterface::class);
    }


    /**
     * @return Card
     */
    protected function createCard()
    {
        return $this->createMock(Card::class);
    }
}
