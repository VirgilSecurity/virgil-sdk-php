<?php
namespace Virgil\Sdk\Cryptography;


use Virgil\Sdk\Contracts\KeyPairInterface;
use Virgil\Sdk\Contracts\PrivateKeyInterface;
use Virgil\Sdk\Contracts\PublicKeyInterface;

/**
 * Class keeps public and private keys and provides access for them.
 */
class VirgilKeyPair implements KeyPairInterface
{
    /** @var PublicKeyInterface $publicKey */
    protected $publicKey;

    /** @var PrivateKeyInterface $privateKey */
    protected $privateKey;


    /**
     * Class constructor.
     *
     * @param PublicKeyInterface  $publicKey
     * @param PrivateKeyInterface $privateKey
     */
    public function __construct(PublicKeyInterface $publicKey, PrivateKeyInterface $privateKey)
    {
        $this->publicKey = $publicKey;
        $this->privateKey = $privateKey;
    }


    /**
     * @inheritdoc
     */
    public function getPublicKey()
    {
        return $this->publicKey;
    }


    /**
     * @inheritdoc
     */
    public function getPrivateKey()
    {
        return $this->privateKey;
    }
}
