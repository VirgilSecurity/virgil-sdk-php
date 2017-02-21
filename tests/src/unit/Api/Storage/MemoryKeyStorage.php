<?php
namespace Virgil\Sdk\Tests\Unit\Api\Storage;


use Virgil\Sdk\Api\Storage\KeyEntry;
use Virgil\Sdk\Contracts\KeyStorageInterface;

class MemoryKeyStorage implements KeyStorageInterface
{
    /** @var array */
    private $storage = [];


    /**
     * @inheritdoc
     */
    public function store(KeyEntry $keyEntry)
    {
        $this->storage[$keyEntry->getName()] = $keyEntry;

        return $this;
    }


    /**
     * @inheritdoc
     */
    public function load($keyName)
    {
        return $this->storage[$keyName];
    }


    /**
     * @inheritdoc
     */
    public function exists($keyName)
    {
        return array_key_exists($keyName, $this->storage);
    }


    /**
     * @inheritdoc
     */
    public function delete($keyName)
    {
        unset($this->storage[$keyName]);

        return $this;
    }
}
