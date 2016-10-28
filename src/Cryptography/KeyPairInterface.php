<?php

namespace Virgil\SDK\Cryptography;


interface KeyPairInterface
{
    /**
     * Get public key
     *
     * @return PublicKeyInterface
     */
    public function getPublicKey();

    /**
     * Get private key
     *
     * @return PrivateKeyInterface
     */
    public function getPrivateKey();
}