<?php
namespace Virgil\Sdk\Cryptography;


use Virgil\Sdk\Contracts\KeyPairInterface;

class VirgilKeyPair implements KeyPairInterface
{
    protected $publicKey;
    protected $privateKey;

    /**
     * VirgilKeyPair constructor.
     *
     * @param PublicKey $publicKey
     * @param PrivateKey $privateKey
     */
    public function __construct(PublicKey $publicKey, PrivateKey $privateKey)
    {
        $this->publicKey = $publicKey;
        $this->privateKey = $privateKey;
    }

    /**
     * @return PublicKey
     */
    public function getPublicKey()
    {
        return $this->publicKey;
    }

    /**
     * @return PrivateKey
     */
    public function getPrivateKey()
    {
        return $this->privateKey;
    }
}
