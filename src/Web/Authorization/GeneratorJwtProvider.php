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

use Virgil\Crypto\Exceptions\VirgilCryptoException;

/**
 * Class GeneratorJwtProvider
 * @package Virgil\Sdk\Web\Authorization
 */
class GeneratorJwtProvider implements AccessTokenProvider
{
    /**
     * @var JwtGenerator
     */
    private $jwtGenerator;
    /**
     * @var array|null
     */
    private $additionalData;
    /**
     * @var string
     */
    private $defaultIdentity;

    /**
     * GeneratorJwtProvider constructor.
     * @param JwtGenerator $jwtGenerator
     * @param $defaultIdentity
     * @param array|null $additionalData
     * @throws GeneratorJWTProviderException
     */
    public function __construct(JwtGenerator $jwtGenerator, $defaultIdentity, array $additionalData = null)
    {
        if(empty($defaultIdentity))
            throw new GeneratorJWTProviderException('Default identity is required');

        $this->jwtGenerator = $jwtGenerator;
        $this->additionalData = $additionalData;
        $this->defaultIdentity = $defaultIdentity;
    }

    /**
     * @return string
     */
    private function getDefaultIdentity()
    {
        return $this->defaultIdentity;
    }


    /**
     * @param TokenContext $context
     *
     * @return AccessToken
     * @throws VirgilCryptoException
     */
    public function getToken(TokenContext $context)
    {
        $identity = empty($context->getIdentity()) ? $this->getDefaultIdentity() : $context->getIdentity();
        return $this->jwtGenerator->generateToken($identity, $this->additionalData);
    }
}
