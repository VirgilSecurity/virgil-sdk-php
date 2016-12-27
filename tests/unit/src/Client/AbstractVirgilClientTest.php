<?php
namespace Virgil\Sdk\Tests\Unit\Client;


use PHPUnit\Framework\TestCase;

use Virgil\Sdk\Client\VirgilClient;
use Virgil\Sdk\Client\VirgilClientParams;

use Virgil\Sdk\Client\VirgilCards\CardsService;

abstract class AbstractVirgilClientTest extends TestCase
{
    /** @var CardsService $cardsServiceMock */
    protected $cardsServiceMock;

    /** @var VirgilClient $virgilClient */
    protected $virgilClient;


    public function setUp()
    {
        $this->cardsServiceMock = $this->getCardsService();
        $this->virgilClient = $this->getVirgilClient($this->cardsServiceMock);
    }


    protected abstract function configureCardsServiceResponse($with, $response);


    private function getCardsService()
    {
        return $this->createMock(CardsService::class);
    }


    private function getVirgilClient($cardsServiceMock)
    {
        $virgilClientParams = new VirgilClientParams('asfja8');

        return new VirgilClient($virgilClientParams, $cardsServiceMock);
    }
}
