<?php
namespace Virgil\Sdk\Tests\Unit\Client;


use Virgil\Sdk\Tests\BaseTestCase;

use Virgil\Sdk\Client\Http\HttpClientInterface;

use Virgil\Sdk\Client\Http\Curl\CurlClient;
use Virgil\Sdk\Client\Http\Curl\CurlRequestFactory;

abstract class AbstractVirgilServiceTest extends BaseTestCase
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
