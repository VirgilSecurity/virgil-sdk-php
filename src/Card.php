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

namespace Virgil\Sdk;

use DateTime;
use Virgil\Crypto\Core\VirgilKeys\VirgilPublicKey;

/**
 * Class Card
 * @package Virgil\Sdk
 */
class Card
{
    /**
     * @var string
     */
    private $ID;
    /**
     * @var string
     */
    private $identity;
    /**
     * @var VirgilPublicKey
     */
    private $publicKey;
    /**
     * @var string
     */
    private $version;
    /**
     * @var DateTime
     */
    private $createdAt;
    /**
     * @var bool
     */
    private $isOutdated;
    /**
     * @var CardSignature[]
     */
    private $signatures;
    /**
     * @var string
     */
    private $contentSnapshot;
    /**
     * @var null|string
     */
    private $previousCardId;
    /**
     * @var null|Card
     */
    private $previousCard;


    /**
     * Class constructor.
     *
     * @param string          $ID
     * @param string          $identity
     * @param VirgilPublicKey $publicKey
     * @param string          $version
     * @param DateTime        $createdAt
     * @param bool            $isOutdated
     * @param CardSignature[] $signatures
     * @param string          $contentSnapshot
     * @param string|null     $previousCardId
     * @param Card|null       $previousCard
     */
    function __construct(
        $ID,
        $identity,
        VirgilPublicKey $publicKey,
        $version,
        DateTime $createdAt,
        $isOutdated,
        $signatures,
        $contentSnapshot,
        $previousCardId = null,
        Card $previousCard = null
    ) {
        $this->ID = $ID;
        $this->identity = $identity;
        $this->publicKey = $publicKey;
        $this->version = $version;
        $this->createdAt = $createdAt;
        $this->isOutdated = $isOutdated;
        $this->signatures = $signatures;
        $this->contentSnapshot = $contentSnapshot;
        $this->previousCardId = $previousCardId;
        $this->previousCard = $previousCard;
    }


    /**
     * @return VirgilPublicKey
     */
    public function getPublicKey()
    {
        return $this->publicKey;
    }


    /**
     * @return string
     */
    public function getID()
    {
        return $this->ID;
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
    public function getVersion()
    {
        return $this->version;
    }


    /**
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }


    /**
     * @return bool
     */
    public function isOutdated()
    {
        return $this->isOutdated;
    }


    /**
     * @return CardSignature[]
     */
    public function getSignatures()
    {
        return $this->signatures;
    }


    /**
     * @return string
     */
    public function getContentSnapshot()
    {
        return $this->contentSnapshot;
    }


    /**
     * @return null|string
     */
    public function getPreviousCardId()
    {
        return $this->previousCardId;
    }


    /**
     * @return null|Card
     */
    public function getPreviousCard()
    {
        return $this->previousCard;
    }
}
