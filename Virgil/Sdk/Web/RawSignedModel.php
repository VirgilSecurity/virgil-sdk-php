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

namespace Virgil\Sdk\Web;


use JsonSerializable;

/**
 * Class RawSignedModel
 * @package Virgil\Sdk\Web
 */
class RawSignedModel implements JsonSerializable
{
    /**
     * @var string
     */
    private $contentSnapshot;
    /**
     * @var RawSignature[]
     */
    private $signatures;


    /**
     * RawSignedModel constructor.
     *
     * @param string         $contentSnapshot
     * @param RawSignature[] $signatures
     */
    function __construct($contentSnapshot, array $signatures)
    {
        $this->contentSnapshot = $contentSnapshot;
        $this->signatures = $signatures;
    }


    /**
     * @param $json
     *
     * @return RawSignedModel
     */
    public static function RawSignedModelFromJson($json)
    {
        $body = json_decode($json, true);
        $signatures = $body['signatures'];

        $rawSignatures = [];
        foreach ($signatures as $signature) {
            $signatureSnapshot = null;
            if (array_key_exists('snapshot', $signature)) {
                $signatureSnapshot = $signature['snapshot'];
            }

            $rawSignatures[] = new RawSignature(
                $signature['signer'], base64_decode($signature['signature']), base64_decode($signatureSnapshot)
            );
        }


        return new RawSignedModel(base64_decode($body['content_snapshot']), $rawSignatures);
    }


    /**
     * @param $base64String
     *
     * @return RawSignedModel
     */
    public static function RawSignedModelFromBase64String($base64String)
    {
        return self::RawSignedModelFromJson(base64_decode($base64String));
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
        return [
            'content_snapshot' => base64_encode($this->contentSnapshot),
            'signatures'       => $this->signatures,
        ];
    }


    /**
     * @return string
     */
    public function getContentSnapshot()
    {
        return $this->contentSnapshot;
    }


    /**
     * @return RawSignature[]
     */
    public function getSignatures()
    {
        return $this->signatures;
    }


    /**
     * @return string
     */
    public function exportAsJson()
    {
        return json_encode($this, JSON_UNESCAPED_SLASHES);
    }


    /**
     * @return string
     */
    public function exportAsBase64String()
    {
        return base64_encode($this->exportAsJson());
    }
}
