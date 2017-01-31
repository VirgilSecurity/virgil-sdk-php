<?php
namespace Virgil\Sdk\Api\Manager;


use Virgil\Sdk\Api\VirgilKeyInterface;

/**
 * Interface allows manipulate with virgil key by provided methods.
 */
interface KeysManagerInterface
{
    /**
     * Generates a new virgil key with default parameters.
     *
     * @return VirgilKeyInterface
     */
    public function generate();


    /**
     * Loads the virgil key from current storage by specified key name.
     *
     * @param string $keyName
     * @param string $keyPassword
     *
     * @return VirgilKeyInterface
     */
    public function load($keyName, $keyPassword);


    /**
     * Removes the virgil key from the storage.
     *
     * @param string $keyName
     *
     * TODO: move to virgil key
     *
     * @return KeysManagerInterface
     */
    public function destroy($keyName);
}
