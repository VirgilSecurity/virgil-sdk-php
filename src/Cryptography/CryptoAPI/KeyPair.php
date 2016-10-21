<?php

namespace Virgil\SDK\Cryptography\CryptoAPI;


interface KeyPair
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