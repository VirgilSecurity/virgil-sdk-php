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

namespace Tests\Unit\Virgil\Sdk;


use PHPUnit\Framework\TestCase;

use Virgil\CryptoApi\CardCrypto;
use Virgil\CryptoApi\PrivateKey;
use Virgil\Sdk\Signer\ModelSigner;
use Virgil\Sdk\Web\RawSignature;
use Virgil\Sdk\Web\RawSignedModel;

class ModelSignerTest extends TestCase
{
    /**
     * @test
     */
    public function sign_appendsToSignedModel_generatedRawSignature()
    {
        $cardCryptoMock = $this->createMock(CardCrypto::class);
        $pk = $this->createMock(PrivateKey::class);
        $cardCryptoMock->expects($this->once())
                       ->method('generateSignature')
                       ->with('{"identity":"Alice"}', $pk)
                       ->willReturn('expected_signature')
        ;

        $modelSigner = new ModelSigner($cardCryptoMock);

        $rawSignedModel = new RawSignedModel(
            '{"identity":"Alice"}', [
                                      new RawSignature('Bob', 'bobs_signature', 'snaps'),
                                  ]
        );
        try {
            $modelSigner->sign($rawSignedModel, 'Alice', $pk);
        } catch (\Virgil\Sdk\VirgilException $e) {
            $this->fail('unexpected exception');
        }

        $signatures = $rawSignedModel->getSignatures();
        $this->assertCount(2, $signatures);
        $this->assertEquals('Alice', $signatures[1]->getSigner());
        $this->assertEquals('expected_signature', $signatures[1]->getSignature());
        $this->assertNull($signatures[1]->getSnapshot());
    }


    /**
     * @test
     */
    public function signWithExtraFields_appendsToSignedModel_generatedRawSignature()
    {
        $cardCryptoMock = $this->createMock(CardCrypto::class);
        $pk = $this->createMock(PrivateKey::class);
        $cardCryptoMock->expects($this->once())
                       ->method('generateSignature')
                       ->with('{"identity":"Alice"}{"extra_name":"value"}', $pk)
                       ->willReturn('expected_signature')
        ;

        $modelSigner = new ModelSigner($cardCryptoMock);

        $rawSignedModel = new RawSignedModel(
            '{"identity":"Alice"}', []
        );
        try {
            $modelSigner->sign($rawSignedModel, 'Alice', $pk, ['extra_name' => 'value']);
        } catch (\Virgil\Sdk\VirgilException $e) {
            $this->fail('unexpected exception');
        }

        $signatures = $rawSignedModel->getSignatures();
        $this->assertCount(1, $signatures);
        $this->assertEquals('Alice', $signatures[0]->getSigner());
        $this->assertEquals('expected_signature', $signatures[0]->getSignature());
        $this->assertEquals('{"extra_name":"value"}', $signatures[0]->getSnapshot());
    }


    /**
     * @test
     *
     * @expectedException \Virgil\Sdk\VirgilException
     */
    public function sign_withExistsSigner_throwsVirgilException()
    {
        $cardCryptoMock = $this->createMock(CardCrypto::class);
        $pk = $this->createMock(PrivateKey::class);

        $modelSigner = new ModelSigner($cardCryptoMock);

        $rawSignedModel = new RawSignedModel(
            '{"identity":"Alice"}', [
                                      new RawSignature('Alice', 'bobs_signature', 'snaps'),
                                  ]
        );

        $modelSigner->sign($rawSignedModel, 'Alice', $pk);
    }

}
