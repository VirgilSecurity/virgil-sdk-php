<?php

namespace Virgil\SDK\Cryptography\CryptoAPI;


class VirgilKeyPair implements KeyPair
{
    /** @var string  */
    private $publicKey;

    /** @var string  */
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

    /**
     * @return string
     */
    public function getPublicKey()
    {
        return $this->publicKey;
    }

    /**
     * @return string
     */
    public function getPrivateKey()
    {
        return $this->privateKey;
    }
}