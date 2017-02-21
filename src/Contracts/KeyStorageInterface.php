<?php
namespace Virgil\Sdk\Contracts;


use Virgil\Sdk\Api\Storage\KeyEntry;


/**
 * This interface describes a storage facility for cryptographic keys.
 */
interface KeyStorageInterface
{
    /**
     * Stores the key to the given alias.
     *
     * @param KeyEntry $keyEntry
     *
     * @return $this
     */
    public function store(KeyEntry $keyEntry);


    /**
     * Loads the key associated with the given alias.
     *
     * @param string $keyName
     *
     * @return KeyEntry
     */
    public function load($keyName);


    /**
     * Checks if the key exists in this storage by given alias.
     *
     * @param string $keyName
     *
     * @return bool
     */
    public function exists($keyName);


    /**
     * Deletes the key associated with the given alias.
     *
     * @param string $keyName
     *
     * @return $this
     */
    public function delete($keyName);

}
