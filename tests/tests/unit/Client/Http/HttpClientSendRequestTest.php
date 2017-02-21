<?php
use Virgil\Sdk\Client\Http\AbstractHttpClient;

use Virgil\Sdk\Client\Http\Requests\DeleteHttpRequest;
use Virgil\Sdk\Client\Http\Requests\GetHttpRequest;
use Virgil\Sdk\Client\Http\Requests\PostHttpRequest;

use Virgil\Sdk\Tests\BaseTestCase;


class HttpClientSendRequestTest extends BaseTestCase
{
    /**
     * @dataProvider getHttpRequestArguments
     *
     * @test
     */
    public function send__httpGetRequest__callsGetHandler($requestUrl, $requestBody, $requestHeaders)
    {
        $httpClientMock = $this->getHttpClient();
        $getHttpRequest = $this->createGetHttpRequest($requestUrl, $requestBody, $requestHeaders);


        $httpClientMock->expects($this->once())
                       ->method('get')
                       ->with($requestUrl, [], $requestHeaders)
        ;


        $httpClientMock->send($getHttpRequest);
    }


    /**
     * @dataProvider getHttpRequestArguments
     *
     * @test
     */
    public function send__httpPostRequest__callsPostHandler($requestUrl, $requestBody, $requestHeaders)
    {
        $httpClientMock = $this->getHttpClient();
        $postHttpRequest = $this->createPostHttpRequest($requestUrl, $requestBody, $requestHeaders);


        $httpClientMock->expects($this->once())
                       ->method('post')
                       ->with($requestUrl, $requestBody, $requestHeaders)
        ;


        $httpClientMock->send($postHttpRequest);
    }


    /**
     * @dataProvider getHttpRequestArguments
     *
     * @test
     */
    public function send__httpDeleteRequest__callsDeleteHandler($requestUrl, $requestBody, $requestHeaders)
    {
        $httpClientMock = $this->getHttpClient();
        $deleteHttpRequest = $this->createDeleteHttpRequest($requestUrl, $requestBody, $requestHeaders);


        $httpClientMock->expects($this->once())
                       ->method('delete')
                       ->with($requestUrl, $requestBody, $requestHeaders)
        ;


        $httpClientMock->send($deleteHttpRequest);
    }


    public function getHttpRequestArguments()
    {
        return [
            [
                'http://immutable.host/card/id/1',
                'Hello Card 1',
                ['UserName' => 'Alice', 'UserRole' => 'receiver'],
            ],
        ];
    }


    protected function getHttpClient()
    {
        return $this->getMockForAbstractClass(AbstractHttpClient::class);
    }


    protected function createGetHttpRequest($requestUrl, $requestBody, $requestHeaders)
    {
        return new GetHttpRequest($requestUrl, $requestBody, $requestHeaders);
    }


    private function createDeleteHttpRequest($requestUrl, $requestBody, $requestHeaders)
    {
        return new DeleteHttpRequest($requestUrl, $requestBody, $requestHeaders);
    }


    private function createPostHttpRequest($requestUrl, $requestBody, $requestHeaders)
    {
        return new PostHttpRequest($requestUrl, $requestBody, $requestHeaders);
    }
}
