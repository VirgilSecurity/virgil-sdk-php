<?php
namespace Virgil\Sdk\Tests\Unit\Client;


use Virgil\Sdk\Client\VirgilServices\Http\HttpClient;
use Virgil\Sdk\Client\VirgilServices\Mapper\AbstractErrorResponseModelMapper;
use Virgil\Sdk\Tests\BaseTestCase;

use Virgil\Sdk\Tests\Unit\Client\Http\HttpResponse;

use Virgil\Sdk\Client\Http\HttpClientInterface;

use Virgil\Sdk\Client\Http\Curl\CurlClient;
use Virgil\Sdk\Client\Http\Curl\CurlRequestFactory;
use Virgil\Sdk\Client\Http\Curl\CurlRequest;
use Virgil\Sdk\Tests\Unit\Client\VirgilServices\Mapper\ErrorResponseModelMapper;

abstract class AbstractVirgilServiceTest extends BaseTestCase
{
    /** @var HttpClientInterface */
    protected $httpCurlClientMock;

    protected $virgilService;

    /** @var  HttpClientInterface */
    protected $httpClient;


    abstract function createErrorResponseModelMapper();


    public function setUp()
    {
        $this->httpCurlClientMock = $this->createCurlClient();
        $this->httpClient = $this->createHttpClient($this->httpCurlClientMock, $this->createErrorResponseModelMapper());

        $this->virgilService = $this->getService($this->httpClient);
    }


    /**
     * @param HttpClientInterface $httpClient
     *
     * @return mixed
     */
    abstract protected function getService(HttpClientInterface $httpClient);


    protected function createCurlClient()
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


    protected function createHttpClient($httpCurlClientMock, $errorResponseModelMapper)
    {

        return new HttpClient(
            $httpCurlClientMock, $errorResponseModelMapper
        );
    }

}
