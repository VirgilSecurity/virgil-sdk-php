<?php

namespace Virgil\Sdk\Cryptography\Core;


/**
 * Interface provides pair relation between public and private keys.
 */
interface KeyPairInterface
{
    /**
     * @return string
     */
    public function getPublicKey();


    /**
     * @return string
     */
    public function getPrivateKey();
}
