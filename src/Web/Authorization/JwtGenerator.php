<?php
/**
 * Copyright (C) 2015-2020 Virgil Security Inc.
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

namespace Virgil\Sdk\Web\Authorization;

use Virgil\Crypto\Core\VirgilKeys\VirgilPrivateKey;
use Virgil\Crypto\Exceptions\VirgilCryptoException;
use Virgil\Crypto\VirgilCrypto;

/**
 * Class JwtGenerator
 * @package Virgil\Sdk\Web\Authorization
 */
class JwtGenerator
{
    /**
     * @var VirgilPrivateKey
     */
    private $apiKey;
    /**
     * @var string
     */
    private $apiPublicKeyIdentifier;
    /**
     * @var VirgilCrypto
     */
    private $virgilCrypto;
    /**
     * @var string
     */
    private $appID;
    /**
     * @var int
     */
    private $ttl;

    /**
     * JwtGenerator constructor.
     * @param VirgilPrivateKey $apiKey
     * @param $apiPublicKeyIdentifier
     * @param VirgilCrypto $virgilCrypto
     * @param $appID
     * @param $ttl
     */
    public function __construct(
        VirgilPrivateKey $apiKey,
        $apiPublicKeyIdentifier,
        VirgilCrypto $virgilCrypto,
        $appID,
        $ttl
    ) {
        $this->apiKey = $apiKey;
        $this->apiPublicKeyIdentifier = $apiPublicKeyIdentifier;
        $this->virgilCrypto = $virgilCrypto;
        $this->appID = $appID;
        $this->ttl = $ttl;
    }


    /**
     * @param string $identity
     * @param array|null $additionalData
     *
     * @return Jwt
     * @throws VirgilCryptoException
     */
    public function generateToken($identity, array $additionalData = null)
    {
        $issuedAt = time();
        $expiresAt = $issuedAt + $this->ttl;

        $jwtHeader = new JwtHeaderContent(
            $this->apiPublicKeyIdentifier,
            Jwt::VirgilJwtAlgorithm,
            Jwt::VirgilJwtContentType,
            Jwt::VirgilJwtType
        );

        $jwtBody = new JwtBodyContent($this->appID, $identity, $issuedAt, $expiresAt, $additionalData);

        $unsignedJwt = new Jwt($jwtHeader, $jwtBody, '');

        $jwtSignature = $this->virgilCrypto->generateSignature($unsignedJwt->getUnsigned(), $this->apiKey);

        return new Jwt($jwtHeader, $jwtBody, $jwtSignature);
    }
}
