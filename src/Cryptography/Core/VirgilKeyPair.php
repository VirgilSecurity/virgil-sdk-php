<?php
namespace Virgil\Sdk\Cryptography\Core;


/**
 * Class aims to store generated public\private keys in pair.
 */
class VirgilKeyPair implements KeyPairInterface
{
    /** @var string $publicKey */
    private $publicKey;

    /** @var string $privateKey */
    private $privateKey;


    /**
     * Class constructor.
     *
     * @param string $publicKey
     * @param string $privateKey
     */
    public function __construct($publicKey, $privateKey)
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
