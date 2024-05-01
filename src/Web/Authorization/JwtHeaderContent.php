<?php
/**
 * Copyright (c) 2015-2024 Virgil Security Inc.
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

declare(strict_types=1);

namespace Virgil\Sdk\Web\Authorization;

use JsonSerializable;


/**
 * Class JwtHeaderContent
 */
class JwtHeaderContent implements JsonSerializable
{
    /**
     * @var string
     */
    protected $contentType;
    /**
     * @var string
     */
    protected $type;
    /**
     * @var string
     */
    private $algorithm;
    /**
     * @var string
     */
    private $apiPublicKeyIdentifier;


    public function __construct(string $apiPublicKeyIdentifier, string $algorithm, string $contentType, string $type)
    {
        $this->apiPublicKeyIdentifier = $apiPublicKeyIdentifier;
        $this->algorithm = $algorithm;
        $this->contentType = $contentType;
        $this->type = $type;
    }


    public function getAlgorithm(): string
    {
        return $this->algorithm;
    }


    public function getApiPublicKeyIdentifier(): string
    {
        return $this->apiPublicKeyIdentifier;
    }


    /**
     * Specify data which should be serialized to JSON
     * @link  http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize(): mixed
    {
        return [
            'alg' => $this->algorithm,
            'kid' => $this->apiPublicKeyIdentifier,
            'typ' => $this->type,
            'cty' => $this->contentType,
        ];
    }


    public function getContentType(): string
    {
        return $this->contentType;
    }


    public function getType(): string
    {
        return $this->type;
    }
}
