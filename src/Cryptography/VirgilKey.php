<?php

namespace Virgil\SDK\Cryptography;


class VirgilKey implements KeyInterface
{
    private $hash;

    /**
     * VirgilKey constructor.
     * @param string $hash
     */
    public function __construct($hash)
    {
        $this->hash = $hash;
    }

    public function getId()
    {
        return $this->hash;
    }
}