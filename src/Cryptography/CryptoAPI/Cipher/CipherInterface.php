<?php

namespace Virgil\SDK\Cryptography\CryptoAPI\Cipher;


interface CipherInterface
{
    /**
     * Add key recipient to cipher
     *
     * @param string $receiverId
     * @param string $publicKey
     * @return mixed
     */
    public function addKeyRecipient($receiverId, $publicKey);
}