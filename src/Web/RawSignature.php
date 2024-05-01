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

namespace Virgil\Sdk\Web;

use JsonSerializable;


/**
 * Class RawSignature
 */
class RawSignature implements JsonSerializable
{
    /**
     * @var string
     */
    private $signer;
    /**
     * @var string|null
     */
    private $snapshot;
    /**
     * @var string
     */
    private $signature;


    public function __construct(string $signer, string $signature, ?string $snapshot = null)
    {
        $this->signer = $signer;
        $this->signature = $signature;
        $this->snapshot = $snapshot;
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
        $jsonData = [
            'signature' => base64_encode($this->signature),
            'signer' => $this->signer,
        ];

        if ($this->snapshot !== null) {
            $jsonData['snapshot'] = base64_encode($this->snapshot);
        }

        return $jsonData;
    }


    /**
     * @return string
     */
    public function getSigner(): string
    {
        return $this->signer;
    }


    /**
     * @return string|null
     */
    public function getSnapshot(): ?string
    {
        return $this->snapshot;
    }


    /**
     * @return string
     */
    public function getSignature(): string
    {
        return $this->signature;
    }
}
