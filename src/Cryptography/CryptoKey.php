<?php
namespace Virgil\Sdk\Cryptography;


class CryptoKey
{
    private $hash;

    /**
     * VirgilKey constructor.
     *
     * @param string $hash
     */
    public function __construct($hash)
    {
        $this->hash = $hash;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->hash;
    }
}
