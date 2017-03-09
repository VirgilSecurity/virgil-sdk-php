<?php
namespace Virgil\Sdk\Tests\Unit\Api\Cards;


use PHPUnit_Framework_MockObject_MockObject;

use Virgil\Sdk\Api\Cards\VirgilCards;
use Virgil\Sdk\Api\Cards\VirgilCardsInterface;

use Virgil\Sdk\Contracts\CryptoInterface;

use Virgil\Sdk\Tests\BaseTestCase;

class AbstractVirgilCardsTest extends BaseTestCase
{
    /** @var PHPUnit_Framework_MockObject_MockObject */
    protected $crypto;


    public function setUp()
    {
        $this->crypto = $this->createCrypto();
    }


    /**
     * @return CryptoInterface
     */
    protected function createCrypto()
    {
        return $this->createMock(CryptoInterface::class);
    }


    /**
     * @param array $virgilCards
     *
     * @return VirgilCardsInterface
     */
    protected function createVirgilCards(array $virgilCards)
    {
        return new VirgilCards($this->crypto, $virgilCards);
    }
}
