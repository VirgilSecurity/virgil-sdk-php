<?php
namespace Virgil\Sdk\Tests\Unit\Client;


use Virgil\Sdk\Tests\BaseTestCase;

use Virgil\Sdk\Tests\Unit\Client\Http\HttpResponse;

use Virgil\Sdk\Client\Http\HttpClientInterface;

use Virgil\Sdk\Client\Http\Curl\CurlClient;
use Virgil\Sdk\Client\Http\Curl\CurlRequestFactory;
use Virgil\Sdk\Client\Http\Curl\CurlRequest;

abstract class AbstractVirgilServiceTest extends BaseTestCase
{
    /** @var HttpClientInterface */
    protected $httpCurlClientMock;

    /** @var */
    protected $virgilService;


    public function setUp()
    {
        $this->httpCurlClientMock = $this->getCurlClient();
        $this->virgilService = $this->getService();
    }


    abstract protected function getService();


    protected function getCurlClient()
    {
        return $this->getMockBuilder(CurlClient::class)
                    ->setConstructorArgs([new CurlRequestFactory()])
                    ->setMethods(['doRequest'])
                    ->getMock()
            ;
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
