<?php
namespace Virgil\Sdk\Tests\Unit\Client\VirgilServices\Http;


use Virgil\Sdk\Client\VirgilServices\UnsuccessfulResponseException;

use Virgil\Sdk\Tests\Unit\Client\Http\HttpResponse;


class VirgilServicesHttpClientTest extends AbstractVirgilServicesHttpClientTest
{

    /**
     * @test
     */
    public function send__whenHttpResponseStatusUnsuccessful__throwsException()
    {
        $virgilServicesHttpClient = $this->getVirgilServicesHttpClient();

        $unsuccessfulResponse = HttpResponse::createHttpClientResponse('400', '', '');

        $this->httpClientMock->expects($this->once())
                             ->method('send')
                             ->willReturn($unsuccessfulResponse)
        ;


        $testCode = function () use ($virgilServicesHttpClient) {
            $virgilServicesHttpClient->send($this->createHttpRequest());
        };


        $this->catchException(UnsuccessfulResponseException::class, $testCode);
    }


    /**
     * @test
     */
    public function send__whenHttpResponseStatusIsSuccessful__returnsResponseBody()
    {
        $virgilServicesHttpClient = $this->getVirgilServicesHttpClient();

        $unsuccessfulResponse = HttpResponse::createHttpClientResponse('200', '', 'hello body');

        $this->httpClientMock->expects($this->once())
                             ->method('send')
                             ->willReturn($unsuccessfulResponse)
        ;


        $response = $virgilServicesHttpClient->send($this->createHttpRequest());


        $this->assertEquals($unsuccessfulResponse->getBody(), $response->getBody());
    }
}
