<?php

namespace Virgil\SDK\Cryptography;


interface KeyPairInterface
{
    /**
     * Get public key
     *
     * @return KeyInterface
     */
    public function getPublicKey();

    /**
     * Get private key
     *
     * @return KeyInterface
     */
    public function getPrivateKey();
}