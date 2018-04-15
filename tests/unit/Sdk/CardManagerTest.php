<?php

use PHPUnit\Framework\TestCase;
use Virgil\CryptoApi\CardCrypto;
use Virgil\CryptoApi\PrivateKey;
use Virgil\CryptoApi\PublicKey;
use Virgil\Sdk\CardManager;
use Virgil\Sdk\CardParams;
use Virgil\Sdk\Signer\ModelSigner;
use Virgil\Sdk\Verification\CardVerifier;
use Virgil\Sdk\Web\Authorization\AccessTokenProvider;
use Virgil\Sdk\Web\CardClient;

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
     * @test
     */
    public function generateRawCard_withCardParams_returnsSelfSignedRawSignedModel()
    {
        $cardCryptoMock = $this->createMock(CardCrypto::class);
        $accessTokenProvider = $this->createMock(AccessTokenProvider::class);
        $cardVerifier = $this->createMock(CardVerifier::class);
        $cardClient = new CardClient();
        $modelSigner = new ModelSigner($cardCryptoMock);

        $cardManager = new CardManager(
            $modelSigner, $cardCryptoMock, $accessTokenProvider, $cardVerifier, $cardClient, function () {
        }
        );

        $cardCryptoMock->expects($this->once())
                       ->method('generateSignature')
                       ->willReturn('expected_signature')
        ;


        $rawSignedModel = $cardManager->generateRawCard(
            CardParams::create(
                [
                    CardParams::PublicKey      => $this->createMock(PublicKey::class),
                    CardParams::PrivateKey     => $this->createMock(PrivateKey::class),
                    CardParams::Identity       => 'Alice',
                    CardParams::PreviousCardID => '23f23f',
                ]
            )
        );

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
        $cardCryptoMock = $this->createMock(CardCrypto::class);
        $accessTokenProvider = $this->createMock(AccessTokenProvider::class);
        $cardVerifier = $this->createMock(CardVerifier::class);
        $cardClient = new CardClient();
        $modelSigner = new ModelSigner($cardCryptoMock);

        $cardManager = new CardManager(
            $modelSigner, $cardCryptoMock, $accessTokenProvider, $cardVerifier, $cardClient, function () {
        }
        );

        $cardCryptoMock->expects($this->once())
                       ->method('generateSignature')
                       ->willReturn('expected_signature')
        ;


        $rawSignedModel = $cardManager->generateRawCard(
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
        );

        $signatures = $rawSignedModel->getSignatures();
        $this->assertNotNull($rawSignedModel->getContentSnapshot());
        $this->assertCount(1, $signatures);
        $this->assertEquals('self', $signatures[0]->getSigner());
        $this->assertEquals('expected_signature', $signatures[0]->getSignature());
        $this->assertEquals('{"extra_a":"val_1","extra_b":"val_2"}', $signatures[0]->getSnapshot());
    }
}
