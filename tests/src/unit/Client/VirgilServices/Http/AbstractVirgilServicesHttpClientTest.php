<?php
namespace Virgil\Sdk\Tests\Unit\Client\VirgilServices\Http;


use Virgil\Sdk\Client\Http\HttpClientInterface;

use Virgil\Sdk\Client\Http\Requests\HttpRequestInterface;

use Virgil\Sdk\Client\VirgilServices\Http\HttpClient;

use Virgil\Sdk\Client\VirgilServices\Mapper\AbstractErrorResponseModelMapper;
use Virgil\Sdk\Client\VirgilServices\Mapper\JsonModelMapperInterface;

use Virgil\Sdk\Tests\BaseTestCase;

abstract class AbstractVirgilServicesHttpClientTest extends BaseTestCase
{
    /** @var HttpClient */
    protected $virgilServicesHttpClient;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $httpErrorResponseModelMapperMock;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $httpClientMock;


    public function setUp()
    {
        $this->httpClientMock = $this->createHttpClient();
        $this->httpErrorResponseModelMapperMock = $this->createErrorResponseModelMapper();

        $this->virgilServicesHttpClient = $this->createVirgilServicesHttpClient(
            $this->httpClientMock,
            $this->httpErrorResponseModelMapperMock
        );
    }


    /**
     * @return HttpClientInterface
     */
    public function getVirgilServicesHttpClient()
    {
        return $this->virgilServicesHttpClient;
    }


    /**
     * @param HttpClientInterface      $httpClient
     * @param JsonModelMapperInterface $errorResponseModelMapper
     *
     * @return HttpClientInterface
     */
    public function createVirgilServicesHttpClient(
        HttpClientInterface $httpClient,
        JsonModelMapperInterface $errorResponseModelMapper
    ) {
        return new HttpClient($httpClient, $errorResponseModelMapper);
    }


    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function createErrorResponseModelMapper()
    {
        return $this->getMockForAbstractClass(AbstractErrorResponseModelMapper::class);
    }


    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function createHttpClient()
    {
        return $this->createMock(HttpClientInterface::class);
    }


    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function createHttpRequest()
    {
        return $this->createMock(HttpRequestInterface::class);
    }

}
