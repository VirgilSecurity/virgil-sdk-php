<?php

namespace Virgil\SDK\Cryptography;


trait KeyStorageTrait
{
    private $keys = [];

    /**
     * Get key from storage.
     *
     * @param KeyInterface $key
     * @return KeyEntryInterface
     */
    protected function getKey(KeyInterface $key)
    {
        if(!$this->hasKey($key)) {
            throw new \InvalidArgumentException('Key is not valid: key id - ' . $key->getId());
        }
        return $this->keys[$key->getId()];
    }

    /**
     * Check if key with given hash exists in storage.
     *
     * @param KeyInterface $key
     * @return bool
     */
    protected function hasKey(KeyInterface $key)
    {
        return array_key_exists($key->getId(), $this->keys);
    }

    /**
     * Put key to storage.
     *
     * @param KeyInterface $key
     * @param KeyEntryInterface $virgilKey
     * @return bool
     */
    protected function putKey(KeyInterface $key, KeyEntryInterface $virgilKey)
    {
        if (!$this->hasKey($key)) {
            $this->keys[$key->getId()] = $virgilKey;
            return true;
        }

        return false;
    }
}