<?php

namespace Virgil\Sdk\Cryptography\CryptoAPI;


class VirgilKeyPair implements KeyPairInterface
{
    private $publicKey;
    private $privateKey;

    /**
     * VirgilKeyPair constructor.
     *
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
