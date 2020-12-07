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

require '../vendor/autoload.php';
require 'VirgilCryptoSample.php';

// Load .env:
(new Dotenv\Dotenv('.', '.env'))->load();

// Init:
$idAlice = "alice@email.com";
$idBob = "bob@email.com";
$message = "Hello, Bob!";

try {
    $vcs = new VirgilCryptoSample();

    // Login as Alice. Store private key and create card
    $vcs->setIdentity($idAlice);
    $cAlice = $vcs->storePrivateKeyAndCreateCard();

    // Login as Bob. Store private key and create card
    $vcs->setIdentity($idBob);
    $cBob = $vcs->storePrivateKeyAndCreateCard();

    // Login as Alice. Sign and encrypt data for Bob
    $vcs->setIdentity($idAlice);
    $messageEncryptedForBob = $vcs->signThenEncryptData($idBob, $message);

    // Login as Bob. Decrypt data and verify signature
    $vcs->setIdentity($idBob);
    $messageDecryptedForBob = $vcs->decryptDataThenVerifySignature($idAlice, $messageEncryptedForBob);

    // Result:
    var_dump(
        $cAlice, $cBob, $messageEncryptedForBob,
        [
            $message => $messageDecryptedForBob,
            "isEquals" => $message==$messageDecryptedForBob
        ]
    );
} catch (\Exception $e) {
    var_dump($e->getMessage(), $e->getCode());
}
