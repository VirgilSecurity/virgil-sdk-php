<?php
namespace Virgil\Sdk\Api\Keys;


use Virgil\Sdk\Contracts\BufferInterface;

/**
 * Interface allows manipulate with virgil key by provided methods.
 */
interface KeysManagerInterface
{
    /**
     * Imports a virgil key from exported buffer.
     *
     * @param BufferInterface $virgilKeyBuffer
     * @param string          $password
     *
     * @return VirgilKeyInterface
     */
    public function import(BufferInterface $virgilKeyBuffer, $password = '');


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
    public function load($keyName, $keyPassword = '');


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
