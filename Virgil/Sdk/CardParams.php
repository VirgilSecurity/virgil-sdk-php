<?php
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

namespace Virgil\Sdk;


use Virgil\CryptoApi\PrivateKey;
use Virgil\CryptoApi\PublicKey;

/**
 * Class CardParams
 * @package Virgil\Sdk
 */
class CardParams
{
    const PublicKey = 'public_key';
    const PrivateKey = 'private_key';
    const Identity = 'identity';
    const PreviousCardID = 'previous_card_ID';
    const ExtraFields = 'extra_fields';

    /**
     * @var PublicKey
     */
    private $publicKey;
    /**
     * @var PrivateKey
     */
    private $privateKey;
    /**
     * @var null|string
     */
    private $identity;
    /**
     * @var null|string
     */
    private $previousCardID;
    /**
     * @var null|array
     */
    private $extraFields;


    /**
     * Class constructor.
     *
     * @param PublicKey  $publicKey
     * @param PrivateKey $privateKey
     * @param string     $identity
     * @param string     $previousCardID
     * @param array      $extraFields
     */
    public function __construct(
        PublicKey $publicKey,
        PrivateKey $privateKey,
        $identity = null,
        $previousCardID = null,
        array $extraFields = null
    ) {
        $this->publicKey = $publicKey;
        $this->privateKey = $privateKey;
        $this->identity = $identity;
        $this->previousCardID = $previousCardID;
        $this->extraFields = $extraFields;
    }


    /**
     * @param array $params
     *
     * @return CardParams
     */
    public static function create(array $params)
    {
        $publicKey = $params[self::PublicKey];
        $privateKey = $params[self::PrivateKey];

        $cardParams = new self($publicKey, $privateKey);

        if (array_key_exists(self::Identity, $params)) {
            $cardParams->identity = $params[self::Identity];
        }
        if (array_key_exists(self::PreviousCardID, $params)) {
            $cardParams->previousCardID = $params[self::PreviousCardID];
        }
        if (array_key_exists(self::ExtraFields, $params)) {
            $cardParams->extraFields = $params[self::ExtraFields];
        }

        return $cardParams;
    }


    /**
     * @return PublicKey
     */
    public function getPublicKey()
    {
        return $this->publicKey;
    }


    /**
     * @return PrivateKey
     */
    public function getPrivateKey()
    {
        return $this->privateKey;
    }


    /**
     * @return null|string
     */
    public function getIdentity()
    {
        return $this->identity;
    }


    /**
     * @return null|string
     */
    public function getPreviousCardID()
    {
        return $this->previousCardID;
    }


    /**
     * @return array|null
     */
    public function getExtraFields()
    {
        return $this->extraFields;
    }

}
