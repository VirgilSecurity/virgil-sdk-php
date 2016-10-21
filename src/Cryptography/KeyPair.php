<?php

namespace Virgil\SDK\Cryptography;


interface KeyPair
{
    /**
     * @return Key
     */
    public function getPublicKey();

    /**
     * @return Key
     */
    public function getPrivateKey();
}