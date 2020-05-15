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

use Virgil\Crypto\Core\VirgilKeys\VirgilPublicKey;

/**
 * Class JwtVerifier
 * @package Virgil\Sdk\Web\Authorization
 */
class JwtVerifier
{
    /**
     * @var PublicKey
     */
    private $apiPublicKey;
    /**
     * @var string
     */
    private $appPublicKeyID;
    /**
     * @var AccessTokenSigner
     */
    private $accessTokenSigner;

    /**
     * JwtVerifier constructor.
     * @param VirgilPublicKey $apiPublicKey
     * @param $appPublicKeyID
     * @param AccessTokenSigner $accessTokenSigner
     */
    public function __construct(VirgilPublicKey $apiPublicKey, $appPublicKeyID, AccessTokenSigner $accessTokenSigner)
    {
        $this->apiPublicKey = $apiPublicKey;
        $this->appPublicKeyID = $appPublicKeyID;
        $this->accessTokenSigner = $accessTokenSigner;
    }


    /**
     * @param Jwt $jwt
     *
     * @return bool
     */
    public function verifyToken(Jwt $jwt)
    {
        $headerContent = $jwt->getHeaderContent();
        if ($headerContent->getApiPublicKeyIdentifier() != $this->appPublicKeyID) {
            return false;
        }
        if ($headerContent->getAlgorithm() != $this->accessTokenSigner->getAlgorithm()) {
            return false;
        }
        if ($headerContent->getContentType() != Jwt::VirgilJwtContentType) {
            return false;
        }
        if ($headerContent->getType() != Jwt::VirgilJwtType) {
            return false;
        }


        return $this->accessTokenSigner->verifyTokenSignature(
            $jwt->getSignatureContent(),
            $jwt->getUnsigned(),
            $this->apiPublicKey
        );
    }
}
