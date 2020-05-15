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

namespace Virgil\Sdk\Web;

/**
 * Class ResponseModel
 * @package Virgil\Sdk\Web
 */
class ResponseModel
{
    const SupersededCardIDHTTPHeader = 'X-Virgil-Is-Superseeded';

    /**
     * @var array
     */
    private $headers;
    /**
     * @var RawSignedModel
     */
    private $rawSignedModel;


    /**
     * ResponseModel constructor.
     *
     * @param string         $stringHeaders
     * @param RawSignedModel $rawSignedModel
     */
    public function __construct($stringHeaders, RawSignedModel $rawSignedModel)
    {
        $headers = [];

        $headersLines = explode("\r\n", $stringHeaders);
        foreach ($headersLines as $headerString) {
            $details = explode(':', trim($headerString), 2);

            if (count($details) == 2) {
                $key = trim($details[0]);
                $value = trim($details[1]);

                $headers[$key] = $value;
            }
        }

        $this->headers = $headers;
        $this->rawSignedModel = $rawSignedModel;
    }


    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }


    /**
     * @return RawSignedModel
     */
    public function getRawSignedModel()
    {
        return $this->rawSignedModel;
    }


    /**
     * @return bool
     */
    public function isOutdated()
    {
        if (array_key_exists(self::SupersededCardIDHTTPHeader, $this->headers)) {
            return $this->headers[self::SupersededCardIDHTTPHeader] == "true";
        }

        return false;
    }
}
