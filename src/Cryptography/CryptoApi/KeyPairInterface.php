<?php

namespace Virgil\Sdk\Cryptography\CryptoAPI;


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
