<?php
namespace Virgil\Sdk\Tests\Unit\Api\Storage;


use PHPUnit\Framework\TestCase;

use Virgil\Sdk\Api\Storage\KeyEntry;
use Virgil\Sdk\Api\Storage\StubKeyStorage;

class StubKeyStorageTest extends TestCase
{

    /**
     * @expectedException \Virgil\Sdk\Exceptions\MethodIsDisabledException
     *
     * @test
     */
    public function store__withKeyEntry__throwsException()
    {
        $stubKeyStorage = $this->createStubKeyStorage();
        $keyEntry = $this->createMock(KeyEntry::class);


        $stubKeyStorage->store($keyEntry);


        //expected exception
    }


    /**
     * @expectedException \Virgil\Sdk\Exceptions\MethodIsDisabledException
     *
     * @test
     */
    public function load__byKeyName__throwsException()
    {
        $stubKeyStorage = $this->createStubKeyStorage();
        $keyName = "key_name";


        $stubKeyStorage->load($keyName);


        //expected exception
    }


    /**
     * @expectedException \Virgil\Sdk\Exceptions\MethodIsDisabledException
     *
     * @test
     */
    public function exists__byKeyName__throwsException()
    {
        $stubKeyStorage = $this->createStubKeyStorage();
        $keyName = "key_name";


        $stubKeyStorage->exists($keyName);


        //expected exception
    }


    /**
     * @expectedException \Virgil\Sdk\Exceptions\MethodIsDisabledException
     *
     * @test
     */
    public function delete__byKeyName__throwsException()
    {
        $stubKeyStorage = $this->createStubKeyStorage();
        $keyName = "key_name";


        $stubKeyStorage->delete($keyName);


        //expected exception
    }


    protected function createStubKeyStorage()
    {
        return new StubKeyStorage();
    }

}
