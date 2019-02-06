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

namespace Virgil\Sdk\Signer;


use Virgil\CryptoApi\CardCrypto;
use Virgil\CryptoApi\PrivateKey;

use Virgil\Sdk\Web\RawSignature;
use Virgil\Sdk\Web\RawSignedModel;

use Virgil\Sdk\VirgilException;


/**
 * Class ModelSigner
 * @package Virgil\Sdk\Signer
 */
class ModelSigner
{
    /**
     * @var CardCrypto
     */
    private $crypto;


    public function __construct(CardCrypto $crypto)
    {
        $this->crypto = $crypto;
    }


    /**
     * @param RawSignedModel $model
     * @param string         $signer
     * @param PrivateKey     $privateKey
     * @param array|null     $extraFields
     *
     * @throws VirgilException
     */
    public function sign(RawSignedModel &$model, $signer, PrivateKey $privateKey, array $extraFields = null)
    {
        $signatures = $model->getSignatures();

        foreach ($signatures as $signature) {
            if ($signature->getSigner() == $signer) {
                throw new VirgilException('The model already has this signature.');
            }
        }

        if ($extraFields != null) {
            $extraFieldsSnapshot = json_encode($extraFields, JSON_UNESCAPED_SLASHES);

            $resultSnapshot = $model->getContentSnapshot();
            $resultSnapshot .= $extraFieldsSnapshot;

            $signature = $this->crypto->generateSignature($resultSnapshot, $privateKey);
            $signatures[] = new RawSignature($signer, $signature, $extraFieldsSnapshot);
        } else {
            $signature = $this->crypto->generateSignature($model->getContentSnapshot(), $privateKey);
            $signatures[] = new RawSignature($signer, $signature);
        }

        $model = new RawSignedModel($model->getContentSnapshot(), $signatures);
    }


    /**
     * @param RawSignedModel $model
     * @param PrivateKey     $privateKey
     * @param array          $extraFields
     *
     * @throws VirgilException
     */
    public function selfSign(RawSignedModel &$model, PrivateKey $privateKey, array $extraFields = null)
    {
        $this->sign($model, 'self', $privateKey, $extraFields);
    }
}
