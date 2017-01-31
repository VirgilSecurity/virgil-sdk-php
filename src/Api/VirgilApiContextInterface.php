<?php
namespace Virgil\Sdk\Api;


use Virgil\Sdk\Contracts\CryptoInterface;
use Virgil\Sdk\Contracts\KeyStorageInterface;

/**
 * Interface provides virgil api dependencies.
 */
interface VirgilApiContextInterface
{
    /**
     * Gets a cryptographic keys storage.
     *
     * @return KeyStorageInterface
     */
    public function getKeyStorage();


    /**
     * Gets a crypto API that represents a set of methods for dealing with low-level.
     *
     * @return CryptoInterface
     */
    public function getCrypto();
}
