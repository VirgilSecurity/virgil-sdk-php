<?php

namespace Virgil\Tests\Unit\Client\Http;


use PHPUnit\Framework\TestCase;
use Virgil\SDK\Client\Http\ClientInterface;
use Virgil\SDK\Client\Http\CurlRequest;
use Virgil\SDK\Client\Http\CurlRequestFactory;
use Virgil\SDK\Client\Http\CurlClient;

class HttpClientTest extends TestCase
{
    /**
     * @dataProvider requestOptionsDataProvider
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
                return $expectedRequest->getOptions() === $actualRequest->getOptions();
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
                    CURLOPT_CUSTOMREQUEST => 'GET',
                    CURLOPT_HTTPGET => true,
                    CURLOPT_RETURNTRANSFER => 1
                ],
                function (ClientInterface $httpClientMock) {
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
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POST => 1,
                    CURLOPT_POSTFIELDS => '{"alice":"bob"}',
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_SAFE_UPLOAD => false
                ],
                function (ClientInterface $httpClientMock) {
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
                    CURLOPT_CUSTOMREQUEST => 'DELETE',
                    CURLOPT_POST => 1,
                    CURLOPT_POSTFIELDS => '{"alice":"bob"}',
                    CURLOPT_RETURNTRANSFER => 1
                ],
                function (ClientInterface $httpClientMock) {
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