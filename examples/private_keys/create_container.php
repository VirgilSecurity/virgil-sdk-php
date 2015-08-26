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

use Virgil\SDK\PrivateKeys\Client as PrivateKeysClient;


const VIRGIL_APPLICATION_TOKEN    = '17da4b6d03fad06954b5dccd82439b10';
const VIRGIL_PUBLIC_KEY_ID        = '5d3a8909-5fe5-2abb-232c-3cf9c277b111';

const VIRGIL_CONTAINER_TYPE       = 'normal';
const VIRGIL_CONTAINER_PASSWORD   = 'password';

const VIRGIL_PRIVATE_KEY_PASSWORD = 'password';

try {

    // Create Keys Service HTTP Client
    $privateKeysClient = new PrivateKeysClient(
        VIRGIL_APPLICATION_TOKEN,
        array(
            'base_url' => 'https://keyring-stg.virgilsecurity.com'
        )
    );

    $privateKeysClient->setHeaders(array(
        'X-VIRGIL-REQUEST-SIGN-PK-ID' => VIRGIL_PUBLIC_KEY_ID
    ));

    echo 'Reading Private Key.' . PHP_EOL;
    $privateKey = file_get_contents(
        '../data/new_private.key'
    );
    echo 'Private Key data successfully readed.' . PHP_EOL;

    // Do service call
    echo 'Call Private Key service to create Container instance.' . PHP_EOL;
    $privateKeysClient->getContainerClient()->createContainer(
        VIRGIL_CONTAINER_TYPE,
        VIRGIL_CONTAINER_PASSWORD,
        $privateKey,
        VIRGIL_PRIVATE_KEY_PASSWORD
    );
    echo 'Container instance successfully created in Private Keys service' . PHP_EOL;

} catch (Exception $e) {

    echo 'Error:' . $e->getMessage();
}