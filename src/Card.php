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

declare(strict_types=1);

namespace Virgil\Sdk;

use DateTime;
use Virgil\Crypto\Core\VirgilKeys\VirgilPublicKey;


/**
 * Class Card
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


    function __construct(
        string $ID,
        string $identity,
        VirgilPublicKey $publicKey,
        string $version,
        DateTime $createdAt,
        bool $isOutdated,
        array $signatures,
        string $contentSnapshot,
        ?string $previousCardId = null,
        ?Card $previousCard = null
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


    public function getPublicKey(): VirgilPublicKey
    {
        return $this->publicKey;
    }


    public function getID(): string
    {
        return $this->ID;
    }


    public function getIdentity(): string
    {
        return $this->identity;
    }


    public function getVersion(): string
    {
        return $this->version;
    }


    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }


    public function isOutdated(): bool
    {
        return $this->isOutdated;
    }


    /**
     * @return CardSignature[]
     */
    public function getSignatures(): array
    {
        return $this->signatures;
    }


    public function getContentSnapshot(): string
    {
        return $this->contentSnapshot;
    }


    public function getPreviousCardId(): ?string
    {
        return $this->previousCardId;
    }


    public function getPreviousCard(): ?Card
    {
        return $this->previousCard;
    }
}
