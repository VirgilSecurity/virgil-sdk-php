<?php
namespace Virgil\Sdk\Cryptography\KeyEntryStorage;


/**
 * Crypto keys storage trait aims to solve problem of keeping keys entry by its reference in restricted field of memory.
 */
trait KeyEntryStorageTrait
{
    /** @var KeyEntry[] $keysStorage */
    private $keysStorage = [];


    /**
     * Gets key entry from memory by its reference.
     *
     * @param KeyReference $keyReference
     *
     * @return KeyEntry
     */
    protected function getKeyEntry(KeyReference $keyReference)
    {
        $id = $keyReference->getId();

        if (!$this->hasKeyEntry($keyReference)) {
            throw new \InvalidArgumentException('Key is not valid: key id - ' . $id);
        }

        return $this->keysStorage[$id];
    }


    /**
     * Checks if key reference exists in memory.
     *
     * @param KeyReference $keyReference
     *
     * @return bool
     */
    protected function hasKeyEntry(KeyReference $keyReference)
    {
        return array_key_exists($keyReference->getId(), $this->keysStorage);
    }


    /**
     * Persists key entry in memory by its reference.
     *
     * @param KeyReference $keyReference
     * @param KeyEntry     $keyEntry
     *
     * TODO Not sure that we need there return true|false statement
     * @return bool
     */
    protected function persistKeyEntry(KeyReference $keyReference, KeyEntry $keyEntry)
    {
        $id = $keyReference->getId();

        if (!$this->hasKeyEntry($keyReference)) {
            $this->keysStorage[$id] = $keyEntry;

            return true;
        }

        return false;
    }
}
