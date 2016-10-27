<?php

namespace Virgil\SDK\Cryptography\CryptoAPI;


interface KeyPairInterface
{
    /**
     * @return string
     */
    public function getPublicKey();

    /**
     * @return string
     */
    public function getPrivateKey();
}