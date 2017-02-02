<?php
namespace Virgil\Sdk\Tests\Unit\Client\VirgilCards;


use Virgil\Sdk\Tests\BaseTestCase;

use Virgil\Sdk\Client\Http\Curl\CurlClient;
use Virgil\Sdk\Client\Http\Curl\CurlRequest;
use Virgil\Sdk\Client\Http\Curl\CurlRequestFactory;

use Virgil\Sdk\Client\Http\HttpClientInterface;

use Virgil\Sdk\Client\VirgilCards\CardsService;
use Virgil\Sdk\Client\VirgilCards\CardsServiceParams;

use Virgil\Sdk\Tests\Unit\Client\Http\HttpResponse;

use Virgil\Sdk\Tests\Unit\Client\VirgilCards\Mapper\MappersCollection;

abstract class AbstractCardsServiceTest extends BaseTestCase
{
    const VIRGIL_CARDS_ACCESS_TOKEN = 'VIRGIL { YOUR_APPLICATION_TOKEN }';

    /** @var HttpClientInterface */
    protected $httpCurlClientMock;

    /** @var CardsService $cardsService */
    protected $cardsService;


    public function setUp()
    {
        $this->httpCurlClientMock = $this->getCurlClient();
        $this->cardsService = $this->getCardsService($this->httpCurlClientMock);
    }


    protected function getCardsService($curlClient)
    {
        $params = new CardsServiceParams(
            'http://immutable.host', 'http://mutable.host/', '/card/actions/search', '/card/', '/card/', '/card/'
        );

        return new CardsService($params, $curlClient, MappersCollection::getMappers());
    }


    protected function getCurlClient()
    {
        /** @var CurlClient $curlClientMock */
        $curlClientMock = $this->getMockBuilder(CurlClient::class)
                               ->setConstructorArgs([new CurlRequestFactory()])
                               ->setMethods(['doRequest'])
                               ->getMock()
        ;

        $curlClientMock->setRequestHeaders(['Authorization' => self::VIRGIL_CARDS_ACCESS_TOKEN]);

        return $curlClientMock;
    }


    protected function configureHttpCurlClientResponse($expectedCurlRequestOptions, $expectedHttpClientResponseArgs)
    {
        $expectedHttpClientResponse = HttpResponse::createHttpClientResponse(...$expectedHttpClientResponseArgs);

        $this->httpCurlClientMock->expects($this->any())
                                 ->method('doRequest')
                                 ->with(
                                     $this->callback(
                                         function (CurlRequest $actualRequest) use ($expectedCurlRequestOptions) {
                                             $options = $actualRequest->getOptions();

                                             return $options == $expectedCurlRequestOptions;
                                         }
                                     )
                                 )
                                 ->willReturn($expectedHttpClientResponse)
        ;
    }

}
