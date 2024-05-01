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

namespace Tests\Integration;

/**
 * Class IntegrationTestsDataProvider
 * @package Virgil\Tests
 * @method STC4__Signature_Extra_Base64
 * @method STC4__Signature_Virgil_Base64
 * @method STC4__Signature_Self_Base64
 * @method STC4__Public_Key_Base64
 * @method STC4__Card_Id
 * @method STC4__As_Json
 * @method STC4__As_String
 * @method STC3__As_Json
 * @method STC3__As_String
 * @method STC3__Card_Id
 * @method STC3__Public_Key_Base64
 * @method STC2__As_Json
 * @method STC2__As_String
 * @method STC1__As_Json
 * @method STC1__As_String
 */
class IntegrationTestsDataProvider
{

    /** @var array $jsonData */
    private $jsonData;


    /**
     * Class constructor.
     *
     * @param $pathToJsonData
     */
    public function __construct($pathToJsonData)
    {
        $this->jsonData = json_decode(file_get_contents($pathToJsonData), true);
    }


    public function __call($name, $a)
    {

        $key = substr($name, 0, 3) . '-' . strtolower(str_replace('__', '.', substr($name, 3)));

        return $this->jsonData[$key];
    }
}
