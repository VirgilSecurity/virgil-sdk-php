<?php

namespace Virgil\SDK\Cryptography;


class VirgilKeyPair implements KeyPairInterface
{
    protected $publicKey;

    protected $privateKey;

    /**
     * VirgilKeyPair constructor.
     * @param VirgilPublicKey $publicKey
     * @param VirgilPrivateKey $privateKey
     */
    public function __construct(VirgilPublicKey $publicKey, VirgilPrivateKey $privateKey)
    {
        $this->publicKey = $publicKey;
        $this->privateKey = $privateKey;
    }

    /**
     * @return VirgilPublicKey
     */
    public function getPublicKey()
    {
        return $this->publicKey;
    }

    /**
     * @return VirgilPrivateKey
     */
    public function getPrivateKey()
    {
        return $this->privateKey;
    }
}