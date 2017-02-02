<?php
namespace Virgil\Sdk\Tests\Unit\Api\Asserts;


trait KeysStorageAsserts
{
    /**
     * @param      $privateKeyName
     * @param bool $isMatches
     */
    protected function assertKeyInStorage($privateKeyName, $isMatches = true)
    {
        $this->assertEquals($isMatches, $this->keyStorage->exists($privateKeyName));
    }
}
