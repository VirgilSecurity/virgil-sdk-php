<?php

namespace Virgil\SDK\Cryptography\CryptoAPI;


class VirgilKeyPair implements KeyPair
{
    private $publicKey;

    private $privateKey;

    /**
     * VirgilKeyPair constructor.
     * @param string $publicKey
     * @param string $privateKey
     */
    public function __construct($publicKey, $privateKey)
    {
        $this->publicKey = $publicKey;
        $this->privateKey = $privateKey;
    }

    public function getPublicKey()
    {
        return $this->publicKey;
    }

    public function getPrivateKey()
    {
        return $this->privateKey;
    }
}