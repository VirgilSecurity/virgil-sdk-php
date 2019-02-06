<?php
/**
 * Copyright (C) 2015-2019 Virgil Security Inc.
 *
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are
 * met:
 *
 *     (1) Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *     (2) Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *     (3) Neither the name of the copyright holder nor the names of its
 *     contributors may be used to endorse or promote products derived from
 *     this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE AUTHOR ''AS IS'' AND ANY EXPRESS OR
 * IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY DIRECT,
 * INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
 * SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)
 * HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT,
 * STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING
 * IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * Lead Maintainer: Virgil Security Inc. <support@virgilsecurity.com>
 */

namespace Tests\Unit\Virgil\Http\Curl;


use Virgil\Http\Constants\RequestMethods;

use Virgil\Http\Curl\CurlClient;
use Virgil\Http\Curl\CurlRequest;
use Virgil\Http\Curl\CurlRequestFactory;

use Virgil\Http\HttpClientInterface;

use PHPUnit\Framework\TestCase;

class CurlClientTest extends TestCase
{
    /**
     * @dataProvider requestOptionsDataProvider
     *
     * @param $factoryDefaultOptions
     * @param $requestExpectedOptions
     * @param $request
     *
     * @test
     */
    public function doRequest__whenCallAvailableRequestMethods__receivesCurlRequestWithValidOptions(
        $factoryDefaultOptions,
        $requestExpectedOptions,
        $request
    ) {
        $curlFactory = new CurlRequestFactory();
        $curlFactory->setDefaultOptions($factoryDefaultOptions);

        $httpClientMock = $this->getMockBuilder(CurlClient::class)
                               ->setConstructorArgs(
                                   [$curlFactory, ['Authorization' => 'VIRGIL { YOUR_APPLICATION_TOKEN }']]
                               )
                               ->setMethods(['doRequest'])
                               ->getMock()
        ;


        $httpClientMock->expects($this->once())
                       ->method('doRequest')
                       ->with(
                           $this->callback(
                               function (CurlRequest $actualRequest) use ($requestExpectedOptions) {
                                   return $requestExpectedOptions == $actualRequest->getOptions();
                               }
                           )
                       )
        ;


        $request($httpClientMock);
    }


    public function requestOptionsDataProvider()
    {
        return [
            [
                [CURLOPT_RETURNTRANSFER => 1],
                [
                    CURLOPT_URL            => '/test/cards?id=card_id_1',
                    CURLOPT_HTTPHEADER     => [
                        'Accept: text/plain; q=0.5,text/html,text/x-c',
                        'Accept-Charset: iso-8859-5,unicode-1-1;q=0.8',
                        'Content-Length: 123',
                        'Authorization: VIRGIL { YOUR_APPLICATION_TOKEN }',
                    ],
                    CURLOPT_CUSTOMREQUEST  => RequestMethods::HTTP_GET,
                    CURLOPT_HTTPGET        => true,
                    CURLOPT_RETURNTRANSFER => 1,
                ],
                function (HttpClientInterface $httpClientMock) {
                    $httpClientMock->get(
                        '/test/cards',
                        ['id' => 'card_id_1'],
                        [
                            'Accept'         => ['text/plain; q=0.5', 'text/html', 'text/x-c'],
                            'Accept-Charset' => ['iso-8859-5', 'unicode-1-1;q=0.8'],
                            'Content-Length' => '123',
                        ]
                    );
                },
            ],
            [
                [CURLOPT_RETURNTRANSFER => 1, CURLOPT_SAFE_UPLOAD => false],
                [
                    CURLOPT_URL            => '/test/card',
                    CURLOPT_HTTPHEADER     => [
                        'Content-Type: application/json',
                        'Content-Length: ' . strlen('{"alice":"bob"}'),
                        'Authorization: VIRGIL { YOUR_APPLICATION_TOKEN }',
                    ],
                    CURLOPT_CUSTOMREQUEST  => RequestMethods::HTTP_POST,
                    CURLOPT_POST           => true,
                    CURLOPT_POSTFIELDS     => '{"alice":"bob"}',
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_SAFE_UPLOAD    => false,
                ],
                function (HttpClientInterface $httpClientMock) {
                    $httpClientMock->post(
                        '/test/card',
                        '{"alice":"bob"}',
                        [
                            'Content-Type'   => ['application/json'],
                            'Content-Length' => strlen('{"alice":"bob"}'),
                        ]
                    );
                },
            ],
            [
                [CURLOPT_RETURNTRANSFER => 1],
                [
                    CURLOPT_URL            => '/test/card',
                    CURLOPT_HTTPHEADER     => [
                        'Content-Type: application/json',
                        'Content-Length: ' . strlen('{"alice":"bob"}'),
                        'Authorization: VIRGIL { MY_TOKEN }',
                    ],
                    CURLOPT_CUSTOMREQUEST  => RequestMethods::HTTP_DELETE,
                    CURLOPT_POST           => true,
                    CURLOPT_POSTFIELDS     => '{"alice":"bob"}',
                    CURLOPT_RETURNTRANSFER => 1,
                ],
                function (HttpClientInterface $httpClientMock) {
                    $httpClientMock->delete(
                        '/test/card',
                        '{"alice":"bob"}',
                        [
                            'Content-Type'   => ['application/json'],
                            'Content-Length' => strlen('{"alice":"bob"}'),
                            'Authorization'  => 'VIRGIL { MY_TOKEN }',
                        ]
                    );
                },
            ],
        ];
    }
}
