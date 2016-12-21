<?php
namespace Virgil\Tests\Unit\Client\Http\Curl;


use PHPUnit\Framework\TestCase;

use Virgil\Sdk\Client\Http\HttpClientInterface;

use Virgil\Sdk\Client\Http\Constants\RequestMethods;

use Virgil\Sdk\Client\Http\Curl\CurlRequest;
use Virgil\Sdk\Client\Http\Curl\CurlRequestFactory;
use Virgil\Sdk\Client\Http\Curl\CurlClient;

class CurlClientTest extends TestCase
{
    /**
     * @dataProvider requestOptionsDataProvider
     *
     * @param $defaultOptions
     * @param $expectedOptions
     * @param $request
     */
    public function testRequestOptions($defaultOptions, $expectedOptions, $request)
    {
        $curlFactory = new CurlRequestFactory();
        $curlFactory->setDefaultOptions($defaultOptions);

        $expectedRequest = new CurlRequest();
        $expectedRequest->setOptions($expectedOptions);

        $httpClientMock = $this->getMockBuilder(CurlClient::class)
            ->setConstructorArgs([$curlFactory, ['Authorization' => 'VIRGIL { YOUR_APPLICATION_TOKEN }']])
            ->setMethods(['doRequest'])
            ->getMock();

        $httpClientMock->expects($this->once())
            ->method('doRequest')
            ->with($this->callback(function (CurlRequest $actualRequest) use ($expectedRequest) {
                return $expectedRequest->getOptions() == $actualRequest->getOptions();
            }));

        $request($httpClientMock);
    }

    public function requestOptionsDataProvider()
    {
        return [
            [
                [CURLOPT_RETURNTRANSFER => 1],
                [
                    CURLOPT_URL => '/test/cards?id=card_id_1',
                    CURLOPT_HTTPHEADER => ['Accept: text/plain; q=0.5,text/html,text/x-c', 'Accept-Charset: iso-8859-5,unicode-1-1;q=0.8', 'Content-Length: 123', 'Authorization: VIRGIL { YOUR_APPLICATION_TOKEN }'],
                    CURLOPT_CUSTOMREQUEST => RequestMethods::HTTP_GET,
                    CURLOPT_HTTPGET => true,
                    CURLOPT_RETURNTRANSFER => 1
                ],
                function (HttpClientInterface $httpClientMock) {
                    $httpClientMock->get('/test/cards', ['id' => 'card_id_1'],
                        [
                            'Accept' => ['text/plain; q=0.5', 'text/html', 'text/x-c'],
                            'Accept-Charset' => ['iso-8859-5', 'unicode-1-1;q=0.8'],
                            'Content-Length' => '123'
                        ]);
                }
            ],
            [
                [CURLOPT_RETURNTRANSFER => 1, CURLOPT_SAFE_UPLOAD => false],
                [
                    CURLOPT_URL => '/test/card',
                    CURLOPT_HTTPHEADER => ['Content-Type: application/json', 'Content-Length: ' . strlen('{"alice":"bob"}'), 'Authorization: VIRGIL { YOUR_APPLICATION_TOKEN }'],
                    CURLOPT_CUSTOMREQUEST => RequestMethods::HTTP_POST,
                    CURLOPT_POST => true,
                    CURLOPT_POSTFIELDS => '{"alice":"bob"}',
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_SAFE_UPLOAD => false
                ],
                function (HttpClientInterface $httpClientMock) {
                    $httpClientMock->post('/test/card', '{"alice":"bob"}',
                        [
                            'Content-Type' => ['application/json'],
                            'Content-Length' => strlen('{"alice":"bob"}')
                        ]);
                }
            ],
            [
                [CURLOPT_RETURNTRANSFER => 1],
                [
                    CURLOPT_URL => '/test/card',
                    CURLOPT_HTTPHEADER => ['Content-Type: application/json', 'Content-Length: ' . strlen('{"alice":"bob"}'), 'Authorization: VIRGIL { MY_TOKEN }'],
                    CURLOPT_CUSTOMREQUEST => RequestMethods::HTTP_DELETE,
                    CURLOPT_POST => true,
                    CURLOPT_POSTFIELDS => '{"alice":"bob"}',
                    CURLOPT_RETURNTRANSFER => 1
                ],
                function (HttpClientInterface $httpClientMock) {
                    $httpClientMock->delete('/test/card', '{"alice":"bob"}',
                        [
                            'Content-Type' => ['application/json'],
                            'Content-Length' => strlen('{"alice":"bob"}'),
                            'Authorization' => 'VIRGIL { MY_TOKEN }'
                        ]);
                }
            ]
        ];
    }
}
