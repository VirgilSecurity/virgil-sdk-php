<?php

namespace Tests\Virgil\Sdk;


use DateTime;
use PHPUnit\Framework\TestCase;
use Virgil\CryptoApi\CardCrypto;
use Virgil\CryptoApi\PrivateKey;
use Virgil\CryptoApi\PublicKey;
use Virgil\Http\HttpClientInterface;
use Virgil\Http\Requests\PostHttpRequest;
use Virgil\Http\Responses\HttpResponse;
use Virgil\Http\Responses\HttpStatusCode;
use Virgil\Sdk\Card;
use Virgil\Sdk\CardClientException;
use Virgil\Sdk\CardManager;
use Virgil\Sdk\CardParams;
use Virgil\Sdk\CardSignature;
use Virgil\Sdk\Signer\ModelSigner;
use Virgil\Sdk\Verification\CardVerifier;
use Virgil\Sdk\Web\Authorization\AccessToken;
use Virgil\Sdk\Web\Authorization\AccessTokenProvider;
use Virgil\Sdk\Web\Authorization\TokenContext;
use Virgil\Sdk\Web\CardClient;
use Virgil\Sdk\Web\RawSignature;
use Virgil\Sdk\Web\RawSignedModel;

/**
 * Copyright (C) 2015-2018 Virgil Security Inc.
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
class CardManagerTest extends TestCase
{

    /**
     * @var CardManager
     */
    protected $cardManager;
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $cardCryptoMock;
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $accessTokenProviderMock;
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $cardVerifierMock;
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $httpClientMock;

    /**
     * @var callable|null
     */
    protected $signCallback = null;


    public function setUp()
    {
        parent::setUp();

        $this->cardCryptoMock = $this->createMock(CardCrypto::class);
        $this->accessTokenProviderMock = $this->createMock(AccessTokenProvider::class);
        $this->cardVerifierMock = $this->createMock(CardVerifier::class);
        $this->httpClientMock = $this->createMock(HttpClientInterface::class);
    }


    /**
     * @test
     */
    public function generateRawCard_withCardParams_returnsSelfSignedRawSignedModel()
    {
        $this->cardCryptoMock->expects($this->once())
                             ->method('generateSignature')
                             ->willReturn('expected_signature')
        ;


        $rawSignedModel = $this->getCardManager()
                               ->generateRawCard(
                                   CardParams::create(
                                       [
                                           CardParams::PublicKey      => $this->createMock(PublicKey::class),
                                           CardParams::PrivateKey     => $this->createMock(PrivateKey::class),
                                           CardParams::Identity       => 'Alice',
                                           CardParams::PreviousCardID => '23f23f',
                                       ]
                                   )
                               )
        ;

        $signatures = $rawSignedModel->getSignatures();
        $this->assertNotNull($rawSignedModel->getContentSnapshot());
        $this->assertCount(1, $signatures);
        $this->assertEquals('self', $signatures[0]->getSigner());
        $this->assertEquals('expected_signature', $signatures[0]->getSignature());
        $this->assertNull($signatures[0]->getSnapshot());
    }


    /**
     * @test
     */
    public function generateRawCard_withCardParamsExtraFields_returnsSelfSignedRawSignedModel()
    {
        $this->cardCryptoMock->expects($this->once())
                             ->method('generateSignature')
                             ->willReturn('expected_signature')
        ;


        $rawSignedModel = $this->getCardManager()
                               ->generateRawCard(
                                   CardParams::create(
                                       [
                                           CardParams::PublicKey   => $this->createMock(PublicKey::class),
                                           CardParams::PrivateKey  => $this->createMock(PrivateKey::class),
                                           CardParams::Identity    => 'Alice',
                                           CardParams::ExtraFields => [
                                               'extra_a' => 'val_1',
                                               'extra_b' => 'val_2',
                                           ],
                                       ]
                                   )
                               )
        ;

        $signatures = $rawSignedModel->getSignatures();
        $this->assertNotNull($rawSignedModel->getContentSnapshot());
        $this->assertCount(1, $signatures);
        $this->assertEquals('self', $signatures[0]->getSigner());
        $this->assertEquals('expected_signature', $signatures[0]->getSignature());
        $this->assertEquals('{"extra_a":"val_1","extra_b":"val_2"}', $signatures[0]->getSnapshot());
    }


    /**
     * @test
     *
     * @expectedException \Virgil\Sdk\CardClientException
     */
    public function publishRawSignedModel_withHttpClientErrorResponse_throwsCardClientException()
    {
        $this->accessTokenProviderMock->expects($this->once())
                                      ->method('getToken')
                                      ->with($this->anything())
                                      ->willReturn($this->createMock(AccessToken::class))
        ;

        $this->httpClientMock->expects($this->once())
                             ->method('send')
                             ->with($this->anything())
                             ->willReturn(new HttpResponse(new HttpStatusCode(500), '', ''))
        ;

        try {
            $this->getCardManager()
                 ->publishRawSignedModel(new RawSignedModel('', []))
            ;
        } catch (CardClientException $e) {
            $this->assertEquals(
                20000,
                $e->getErrorCode()
            );
            $this->assertEquals(
                "error during request serving",
                $e->getErrorMessage()
            );

            throw $e;
        }
    }


    /**
     * @test
     */
    public function publishRawSignedModel_withAccessToken_returnsCardWithID()
    {
        $this->signCallback = function (RawSignedModel &$model) {
            $signatures = $model->getSignatures();
            $signatures[] = new RawSignature("callback", "sign");
            $model = new  RawSignedModel($model->getContentSnapshot(), $signatures);
        };

        $this->cardVerifierMock->expects($this->once())
                               ->method("verifyCard")
                               ->with($this->anything())
                               ->willReturn(true)
        ;

        $this->cardCryptoMock->expects($this->once())
                             ->method("generateSHA512")
                             ->with(
                                 '{"identity":"Alice-6cadaa68f091d3d3626a","public_key":"MCowBQYDK2VwAyEAD7BNeVDbuZ9FPOJuCfvQBVelrajspfTomvRpN1DYVn0=","version":"5.0","created_at":1523827888}'
                             )
                             ->willReturn(
                                 base64_decode(
                                     "AQVcYCMpp3HfyLx6X/HC7lcdFpw2s8UoFwnl1PeRNV9OOmt6onnlFg9LXqLzihLKcrjcb1zMNqhg8BMcGQfQgQ=="
                                 )
                             )
        ;

        $this->cardCryptoMock->expects($this->once())
                             ->method("importPublicKey")
                             ->with("MCowBQYDK2VwAyEAD7BNeVDbuZ9FPOJuCfvQBVelrajspfTomvRpN1DYVn0=")
                             ->willReturn($this->createMock(PublicKey::class))
        ;

        $this->httpClientMock->expects($this->once())
                             ->method('send')
                             ->with(
                                 new PostHttpRequest(
                                     "http://service.url",
                                     '{"content_snapshot":"eyJpZGVudGl0eSI6IkFsaWNlLTZjYWRhYTY4ZjA5MWQzZDM2MjZhIiwicHVibGljX2tleSI6Ik1Db3dCUVlESzJWd0F5RUFEN0JOZVZEYnVaOUZQT0p1Q2Z2UUJWZWxyYWpzcGZUb212UnBOMURZVm4wPSIsInZlcnNpb24iOiI1LjAiLCJjcmVhdGVkX2F0IjoxNTIzODI3ODg4fQ==","signatures":[{"signer":"self","signature":"MFEwDQYJYIZIAWUDBAIDBQAEQDBbYZkTu7vt5AKTcCPJ685nMuQCivQZeMR+6jmmJY21/k5B4xEs5A7HF293fbYV/6ZlqdTAsPjjQuMXPNU6pwA="},{"signer":"callback","signature":"c2lnbg=="}]}',
                                     ["Authorization" => "Virgil access_token_string"]
                                 )
                             )
                             ->willReturn(
                                 new HttpResponse(
                                     new HttpStatusCode(201), '', '
                                            {
                                              "content_snapshot": "eyJpZGVudGl0eSI6IkFsaWNlLTZjYWRhYTY4ZjA5MWQzZDM2MjZhIiwicHVibGljX2tleSI6Ik1Db3dCUVlESzJWd0F5RUFEN0JOZVZEYnVaOUZQT0p1Q2Z2UUJWZWxyYWpzcGZUb212UnBOMURZVm4wPSIsInZlcnNpb24iOiI1LjAiLCJjcmVhdGVkX2F0IjoxNTIzODI3ODg4fQ==",
                                              "signatures": [
                                                {
                                                  "signer": "self",
                                                  "signature": "MFEwDQYJYIZIAWUDBAIDBQAEQDBbYZkTu7vt5AKTcCPJ685nMuQCivQZeMR+6jmmJY21/k5B4xEs5A7HF293fbYV/6ZlqdTAsPjjQuMXPNU6pwA="
                                                },
                                                {
                                                  "signer": "virgil",
                                                  "signature": "MFEwDQYJYIZIAWUDBAIDBQAEQAOiE0Y29s/rPAtxjV0HZsGf3ETQnjCFSndvac2KPNP4rXUOJ2NOj7VgRAkc3izKQpDs+Bd1YNy0hZeh36GcJQc="
                                                }
                                              ]
                                            }'
                                 )
                             )
        ;

        $accessTokenMock = $this->createMock(AccessToken::class);
        $accessTokenMock->method("__toString")
                        ->willReturn("access_token_string")
        ;

        $this->accessTokenProviderMock->expects($this->once())
                                      ->method('getToken')
                                      ->with(new TokenContext("Alice-6cadaa68f091d3d3626a", 'publish'))
                                      ->willReturn($accessTokenMock)
        ;

        $contentSnapshot = '{"identity":"Alice-6cadaa68f091d3d3626a","public_key":"MCowBQYDK2VwAyEAD7BNeVDbuZ9FPOJuCfvQBVelrajspfTomvRpN1DYVn0=","version":"5.0","created_at":1523827888}';
        $signatures = [
            new RawSignature(
                "self", base64_decode(
                          "MFEwDQYJYIZIAWUDBAIDBQAEQDBbYZkTu7vt5AKTcCPJ685nMuQCivQZeMR+6jmmJY21/k5B4xEs5A7HF293fbYV/6ZlqdTAsPjjQuMXPNU6pwA="
                      )
            ),
        ];

        $card = $this->getCardManager()
                     ->publishRawSignedModel(new RawSignedModel($contentSnapshot, $signatures))
        ;

        $this->assertEquals("01055c602329a771dfc8bc7a5ff1c2ee571d169c36b3c5281709e5d4f791355f", $card->getID());
    }


    /**
     * @test
     */
    public function importCard_fromRawSignedModel_returnsCard()
    {
        $contentSnapshot = '{"identity":"Alice-6cadaa68f091d3d3626a","public_key":"MCowBQYDK2VwAyEAD7BNeVDbuZ9FPOJuCfvQBVelrajspfTomvRpN1DYVn0=","version":"5.0","created_at":1523827888}';
        $signatures = [
            new RawSignature(
                "self", base64_decode(
                          "MFEwDQYJYIZIAWUDBAIDBQAEQDBbYZkTu7vt5AKTcCPJ685nMuQCivQZeMR+6jmmJY21/k5B4xEs5A7HF293fbYV/6ZlqdTAsPjjQuMXPNU6pwA="
                      )
            ),
        ];

        $model = new RawSignedModel($contentSnapshot, $signatures);

        $this->cardVerifierMock->expects($this->once())
                               ->method("verifyCard")
                               ->with($this->anything())
                               ->willReturn(true)
        ;

        $this->cardCryptoMock->expects($this->once())
                             ->method("importPublicKey")
                             ->with("MCowBQYDK2VwAyEAD7BNeVDbuZ9FPOJuCfvQBVelrajspfTomvRpN1DYVn0=")
                             ->willReturn($this->createMock(PublicKey::class))
        ;

        $this->cardCryptoMock->expects($this->once())
                             ->method("generateSHA512")
                             ->with(
                                 '{"identity":"Alice-6cadaa68f091d3d3626a","public_key":"MCowBQYDK2VwAyEAD7BNeVDbuZ9FPOJuCfvQBVelrajspfTomvRpN1DYVn0=","version":"5.0","created_at":1523827888}'
                             )
                             ->willReturn(
                                 base64_decode(
                                     "AQVcYCMpp3HfyLx6X/HC7lcdFpw2s8UoFwnl1PeRNV9OOmt6onnlFg9LXqLzihLKcrjcb1zMNqhg8BMcGQfQgQ=="
                                 )
                             )
        ;

        $card = $this->getCardManager()
                     ->importCard($model)
        ;


        $this->assertEquals("01055c602329a771dfc8bc7a5ff1c2ee571d169c36b3c5281709e5d4f791355f", $card->getID());
        $this->assertEquals($this->createMock(PublicKey::class), $card->getPublicKey());
        $this->assertEquals('Alice-6cadaa68f091d3d3626a', $card->getIdentity());
        $this->assertEquals('5.0', $card->getVersion());
        $this->assertEquals(new DateTime("2018-04-15 21:31:28"), $card->getCreatedAt());
        $this->assertEquals(false, $card->isOutdated());
        $this->assertEquals(
            '{"identity":"Alice-6cadaa68f091d3d3626a","public_key":"MCowBQYDK2VwAyEAD7BNeVDbuZ9FPOJuCfvQBVelrajspfTomvRpN1DYVn0=","version":"5.0","created_at":1523827888}',
            $card->getContentSnapshot()
        );
        $this->assertEquals(
            [
                new CardSignature(
                    "self", base64_decode(
                              "MFEwDQYJYIZIAWUDBAIDBQAEQDBbYZkTu7vt5AKTcCPJ685nMuQCivQZeMR+6jmmJY21/k5B4xEs5A7HF293fbYV/6ZlqdTAsPjjQuMXPNU6pwA="
                          )
                ),
            ],
            $card->getSignatures()
        );

        $this->assertNull($card->getPreviousCard());
        $this->assertNull($card->getPreviousCardId());
    }


    /**
     * @test
     */
    public function exportCard_asRawSignedModel_returnsRawSignedModel()
    {
        $expectedContentSnapshot = '{"identity":"Alice-6cadaa68f091d3d3626a","public_key":"MCowBQYDK2VwAyEAD7BNeVDbuZ9FPOJuCfvQBVelrajspfTomvRpN1DYVn0=","version":"5.0","created_at":1523827888}';
        $expectedSignatures = [
            new RawSignature(
                "self", base64_decode(
                          "MFEwDQYJYIZIAWUDBAIDBQAEQDBbYZkTu7vt5AKTcCPJ685nMuQCivQZeMR+6jmmJY21/k5B4xEs5A7HF293fbYV/6ZlqdTAsPjjQuMXPNU6pwA="
                      )
            ),
        ];

        $card = new Card(
            '01055c602329a771dfc8bc7a5ff1c2ee571d169c36b3c5281709e5d4f791355f',
            'Alice-6cadaa68f091d3d3626a',
            $this->createMock(PublicKey::class),
            '5.0',
            new DateTime("2018-04-15 21:31:28"),
            false,
            [
                new CardSignature(
                    "self", base64_decode(
                              "MFEwDQYJYIZIAWUDBAIDBQAEQDBbYZkTu7vt5AKTcCPJ685nMuQCivQZeMR+6jmmJY21/k5B4xEs5A7HF293fbYV/6ZlqdTAsPjjQuMXPNU6pwA="
                          )
                ),
            ],
            '{"identity":"Alice-6cadaa68f091d3d3626a","public_key":"MCowBQYDK2VwAyEAD7BNeVDbuZ9FPOJuCfvQBVelrajspfTomvRpN1DYVn0=","version":"5.0","created_at":1523827888}'
        );

        $rawSignedModel = $this->getCardManager()
                               ->exportCardAsRawCard($card)
        ;

        $this->assertEquals($expectedContentSnapshot, $rawSignedModel->getContentSnapshot());
        $this->assertEquals($expectedSignatures, $rawSignedModel->getSignatures());
    }


    /**
     * @return CardManager
     */
    protected function getCardManager()
    {
        $cardClient = new CardClient("http://service.url", $this->httpClientMock);
        $modelSigner = new ModelSigner($this->cardCryptoMock);

        return new CardManager(
            $modelSigner,
            $this->cardCryptoMock,
            $this->accessTokenProviderMock,
            $this->cardVerifierMock,
            $cardClient,
            $this->signCallback
        );
    }
}
