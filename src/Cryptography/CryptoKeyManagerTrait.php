<?php
namespace Virgil\Sdk\Cryptography;


trait CryptoKeyManagerTrait
{
    private $keys = [];

    /**
     * Get key from storage.
     *
     * @param CryptoKey $key
     * @return CryptoKeyEntry
     */
    protected function getKey(CryptoKey $key)
    {
        if (!$this->hasKey($key)) {
            throw new \InvalidArgumentException('Key is not valid: key id - ' . $key->getId());
        }
        return $this->keys[$key->getId()];
    }

    /**
     * Check if key with given hash exists in storage.
     *
     * @param CryptoKey $key
     * @return bool
     */
    protected function hasKey(CryptoKey $key)
    {
        return array_key_exists($key->getId(), $this->keys);
    }

    /**
     * Put key to storage.
     *
     * @param CryptoKey $key
     * @param CryptoKeyEntry $virgilKey
     * @return bool
     */
    protected function putKey(CryptoKey $key, CryptoKeyEntry $virgilKey)
    {
        if (!$this->hasKey($key)) {
            $this->keys[$key->getId()] = $virgilKey;
            return true;
        }

        return false;
    }
}
