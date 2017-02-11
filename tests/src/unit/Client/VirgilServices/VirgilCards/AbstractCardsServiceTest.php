<?php
namespace Virgil\Sdk\Tests\Unit\Client\VirgilServices\VirgilCards;


use Virgil\Sdk\Tests\Unit\Client\AbstractVirgilServiceTest;

use Virgil\Sdk\Tests\Unit\Client\VirgilServices\VirgilCards\Mapper\MappersCollection;

use Virgil\Sdk\Client\VirgilServices\VirgilCards\CardsService;
use Virgil\Sdk\Client\VirgilServices\VirgilCards\CardsServiceParams;

abstract class AbstractCardsServiceTest extends AbstractVirgilServiceTest
{
    const VIRGIL_CARDS_ACCESS_TOKEN = 'VIRGIL { YOUR_APPLICATION_TOKEN }';

    /** @var CardsService */
    protected $virgilService;


    public function setUp()
    {
        parent::setUp();

        $this->httpCurlClientMock->setRequestHeaders(['Authorization' => self::VIRGIL_CARDS_ACCESS_TOKEN]);
    }


    /**
     * @return CardsService
     */
    protected function getService()
    {
        $params = new CardsServiceParams(
            'http://immutable.host', 'http://mutable.host/', '/card/actions/search', '/card/', '/card/', '/card/'
        );

        return new CardsService($params, $this->httpCurlClientMock, MappersCollection::getMappers());
    }
}
