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

namespace Virgil\Sdk\Web\Authorization;


use JsonSerializable;


/**
 * Class JwtBodyContent
 * @package Virgil\Sdk\Web\Authorization
 */
class JwtBodyContent implements JsonSerializable
{
    /**
     * @var string
     */
    private $appID;
    /**
     * @var string
     */
    private $identity;
    /**
     * @var int
     */
    private $issuedAt;
    /**
     * @var int
     */
    private $expiresAt;
    /**
     * @var array|null
     */
    private $additionalData;


    /**
     * JwtBodyContent constructor.
     *
     * @param string     $appID
     * @param string     $identity
     * @param int        $issuedAt
     * @param int        $expiresAt
     * @param array|null $additionalData
     */
    public function __construct($appID, $identity, $issuedAt, $expiresAt, array $additionalData = null)
    {
        $this->appID = $appID;
        $this->identity = $identity;
        $this->issuedAt = $issuedAt;
        $this->expiresAt = $expiresAt;
        $this->additionalData = $additionalData;
    }


    /**
     * @return string
     */
    public function getIdentity()
    {
        return $this->identity;
    }


    /**
     * @return string
     */
    public function getAppID()
    {
        return $this->appID;
    }


    /**
     * @return int
     */
    public function getIssuedAt()
    {
        return $this->issuedAt;
    }


    /**
     * @return int
     */
    public function getExpiresAt()
    {
        return $this->expiresAt;
    }


    /**
     * @return array
     */
    public function getAdditionalData()
    {
        return $this->additionalData;
    }


    /**
     * Specify data which should be serialized to JSON
     * @link  http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        $json = [
            'iss' => 'virgil-' . $this->appID,
            'sub' => 'identity-' . $this->identity,
            'iat' => $this->issuedAt,
            'exp' => $this->expiresAt,
        ];

        if ($this->additionalData != null) {
            $json['ada'] = $this->additionalData;
        }

        return $json;
    }
}
