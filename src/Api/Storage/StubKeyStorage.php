<?php
namespace Virgil\Sdk\Api\Storage;


use Virgil\Sdk\Contracts\KeyStorageInterface;

use Virgil\Sdk\Exceptions\MethodIsDisabledException;

/**
 * Class is designed to stub public methods for key storage.
 * This class should use as default interface implementation.
 */
class StubKeyStorage implements KeyStorageInterface
{
    /**
     * @inheritdoc
     */
    public function store(KeyEntry $keyEntry)
    {
        throw new MethodIsDisabledException(__METHOD__);
    }


    /**
     * @inheritdoc
     */
    public function load($keyName)
    {
        throw new MethodIsDisabledException(__METHOD__);
    }


    /**
     * @inheritdoc
     */
    public function exists($keyName)
    {
        throw new MethodIsDisabledException(__METHOD__);
    }


    /**
     * @inheritdoc
     */
    public function delete($keyName)
    {
        throw new MethodIsDisabledException(__METHOD__);
    }
}
