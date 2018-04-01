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


/**
 * Class CardSignature
 * @package Virgil\Sdk
 */
class CardSignature
{
    /**
     * @var string
     */
    private $signer;
    /**
     * @var string
     */
    private $signature;
    /**
     * @var string
     */
    private $snapshot;
    /**
     * @var array|null
     */
    private $extraFields;


    /**
     * Class constructor.
     *
     * @param string     $signer
     * @param string     $signature
     * @param string     $snapshot
     * @param array|null $extraFields
     */
    function __construct($signer, $signature, $snapshot, array $extraFields = null)
    {
        $this->signer = $signer;
        $this->signature = $signature;
        $this->snapshot = $snapshot;
        $this->extraFields = $extraFields;
    }


    /**
     * @return string
     */
    public function getSigner()
    {
        return $this->signer;
    }


    /**
     * @return string
     */
    public function getSignature()
    {
        return $this->signature;
    }


    /**
     * @return string
     */
    public function getSnapshot()
    {
        return $this->snapshot;
    }


    /**
     * @return array|null
     */
    public function getExtraFields()
    {
        return $this->extraFields;
    }
}
