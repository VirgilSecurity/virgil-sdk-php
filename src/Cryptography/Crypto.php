<?php

namespace Virgil\SDK\Cryptography;


interface Crypto
{
    /**
     * @param integer $cryptoType
     * @return KeyPair
     */
    public function generateKeys($cryptoType);
}