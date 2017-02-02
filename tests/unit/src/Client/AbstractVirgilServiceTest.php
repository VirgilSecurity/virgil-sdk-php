<?php
namespace Virgil\Sdk\Tests\Unit\Client;


use PHPUnit\Framework\TestCase;
use Virgil\Sdk\Client\Http\Curl\CurlClient;
use Virgil\Sdk\Client\Http\Curl\CurlRequestFactory;
use Virgil\Sdk\Client\Http\HttpClientInterface;

abstract class AbstractVirgilServiceTest extends TestCase
{
    /** @var HttpClientInterface */
    protected $httpCurlClientMock;


    public function setUp()
    {
        $this->httpCurlClientMock = $this->getCurlClient();
    }


    abstract protected function getService($curlClient);


    protected function getCurlClient()
    {
        /** @var CurlClient $curlClientMock */
        $curlClientMock = $this->getMockBuilder(CurlClient::class)
                               ->setConstructorArgs([new CurlRequestFactory()])
                               ->setMethods(['doRequest'])
                               ->getMock()
        ;

        //$curlClientMock->setRequestHeaders(['Authorization' => self::VIRGIL_CARDS_ACCESS_TOKEN]);

        return $curlClientMock;
    }

}
