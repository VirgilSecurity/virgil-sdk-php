<?php

/**
 * Copyright (C) 2014 Virgil Security Inc.
 *
 * Lead Maintainer: Virgil Security Inc. <support@virgilsecurity.com>
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
 */

require_once '../vendor/autoload.php';

use Virgil\SDK\Common\Utils\GUID,
    Virgil\SDK\Keys\Client as KeysClient;


const VIRGIL_APPLICATION_TOKEN  = '17da4b6d03fad06954b5dccd82439b10';

const VIRGIL_UUID = 'aa2141ee-8a50-a7c4-3e4c-513b67918053';
const VIRGIL_CONFIRMATION_CODE = 'J9Y0D5';


try {

    // Create Keys Service HTTP Client
    $keysClient = new KeysClient(
        VIRGIL_APPLICATION_TOKEN
    );

    // Do service call
    echo 'Call Keys service to confirm User Data.' . PHP_EOL;
    $keysClient->getUserDataClient()->persistUserData(
        VIRGIL_UUID,
        VIRGIL_CONFIRMATION_CODE
    );
    echo 'User Data successfully confirmed.' . PHP_EOL;

} catch (Exception $e) {

    echo 'Error:' . $e->getMessage();
}