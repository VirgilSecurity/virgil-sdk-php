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

namespace Virgil\Sdk\Web\Authorization;


use InvalidArgumentException;


/**
 * Class Jwt
 * @package Virgil\Sdk\Web\Authorization
 */
class Jwt implements AccessToken
{

    const VirgilJwtType = 'JWT';
    const VirgilJwtContentType = 'virgil-jwt;v=1';

    /**
     * @var JwtParser
     */
    protected $jwtParser;
    /**
     * @var JwtHeaderContent
     */
    private $headerContent;
    /**
     * @var JwtBodyContent
     */
    private $bodyContent;
    /**
     * @var string
     */
    private $signatureContent;


    /**
     * Jwt constructor.
     *
     * @param JwtHeaderContent $headerContent
     * @param JwtBodyContent   $bodyContent
     * @param string           $signature
     */
    public function __construct(JwtHeaderContent $headerContent, JwtBodyContent $bodyContent, $signature)
    {
        $this->headerContent = $headerContent;
        $this->bodyContent = $bodyContent;
        $this->signatureContent = $signature;
        $this->jwtParser = new JwtParser();
    }


    /**
     * @param string $token
     *
     * @return Jwt
     */
    public static function fromString($token)
    {
        $jwtParser = new JwtParser();
        $tokenParts = explode('.', $token);
        if (count($tokenParts) != 3) {
            throw new InvalidArgumentException("JWT parse failed: " . $token);
        }
        $tokenBase64Header = $tokenParts[0];
        $tokenBase64Body = $tokenParts[1];
        $tokenBase64Signature = $tokenParts[2];

        $urlSafeBase64Decode = function ($data) {
            return base64_decode(strtr($data, '-_', '+/'));
        };

        $tokenJsonHeader = $urlSafeBase64Decode($tokenBase64Header);
        $tokenJsonBody = $urlSafeBase64Decode($tokenBase64Body);
        $signature = $urlSafeBase64Decode($tokenBase64Signature);

        return new Jwt(
            $jwtParser->parseJwtHeaderContent($tokenJsonHeader),
            $jwtParser->parseJwtBodyContent($tokenJsonBody),
            $signature
        );
    }


    /**
     * @return bool
     */
    public function isExpired()
    {
        return $this->bodyContent->getExpiresAt() < time();
    }


    /**
     * @return string
     */
    public function identity()
    {
        return $this->bodyContent->getIdentity();
    }


    /**
     * @return string
     */
    public function __toString()
    {
        if ($this->signatureContent != '') {
            $jwtBase64Signature = $this->urlSafeBase64Encode($this->signatureContent);

            return $this->getUnsigned() . "." . $jwtBase64Signature;
        }

        return $this->getUnsigned();
    }


    /**
     * @return string
     */
    public function getSignatureContent()
    {
        return $this->signatureContent;
    }


    /**
     * @return string
     */
    public function getUnsigned()
    {
        $jwtBase64Body = $this->urlSafeBase64Encode($this->jwtParser->buildJwtBody($this->bodyContent));
        $jwtBase64Header = $this->urlSafeBase64Encode($this->jwtParser->buildJwtHeader($this->headerContent));

        return $jwtBase64Header . "." . $jwtBase64Body;
    }


    /**
     * @return JwtBodyContent
     */
    public function getBodyContent()
    {
        return $this->bodyContent;
    }


    /**
     * @return JwtHeaderContent
     */
    public function getHeaderContent()
    {
        return $this->headerContent;
    }


    private function urlSafeBase64Encode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}
