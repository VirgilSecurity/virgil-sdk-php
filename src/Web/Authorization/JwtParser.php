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

namespace Virgil\Sdk\Web\Authorization;


/**
 * Class JwtParser
 * @package Virgil\Sdk\Web\Authorization
 */
class JwtParser
{
    /**
     * @param string $jwtBodyString
     *
     * @return JwtBodyContent
     */
    public function parseJwtBodyContent($jwtBodyString)
    {
        $tokenJsonBody = json_decode($jwtBodyString, true);

        $iss = str_replace('virgil-', '', $tokenJsonBody['iss']);
        $sub = str_replace('identity-', '', $tokenJsonBody['sub']);
        $iat = $tokenJsonBody['iat'];
        $exp = $tokenJsonBody['exp'];

        if (array_key_exists('ada', $tokenJsonBody)) {
            return new JwtBodyContent($iss, $sub, $iat, $exp, $tokenJsonBody['ada']);
        }

        return new JwtBodyContent($iss, $sub, $iat, $exp);
    }


    /**
     * @param string $jwtHeaderString
     *
     * @return JwtHeaderContent
     */
    public function parseJwtHeaderContent($jwtHeaderString)
    {
        $tokenJsonHeader = json_decode($jwtHeaderString, true);

        return new JwtHeaderContent(
            $tokenJsonHeader['kid'],
            $tokenJsonHeader['alg'],
            $tokenJsonHeader['cty'],
            $tokenJsonHeader['typ']
        );
    }


    /**
     * @param JwtBodyContent $jwtBodyContent
     *
     * @return string
     */
    public function buildJwtBody(JwtBodyContent $jwtBodyContent)
    {
        return json_encode($jwtBodyContent);
    }


    /**
     * @param JwtHeaderContent $jwtHeaderContent
     *
     * @return string
     */
    public function buildJwtHeader(JwtHeaderContent $jwtHeaderContent)
    {
        return json_encode($jwtHeaderContent);
    }


}
