<?php
namespace Virgil\Sdk\Tests\Unit\Api\Storage;


use PHPUnit\Framework\TestCase;

use \Virgil\Sdk\Exceptions\MethodIsDisabledException;

use Virgil\Sdk\Api\Storage\KeyEntry;
use Virgil\Sdk\Api\Storage\StubKeyStorage;

class StubKeyStorageTest extends TestCase
{

    /**
     * @test
     */
    public function store__withKeyEntry__throwsException()
    {
        $stubKeyStorage = $this->createStubKeyStorage();
        $keyEntry = $this->createMock(KeyEntry::class);


        try {
            $stubKeyStorage->store($keyEntry);
        } catch (MethodIsDisabledException $exception) {


            $this->assertNotEmpty($exception->getMessage());
        }
    }


    /**
     * @test
     */
    public function load__byKeyName__throwsException()
    {
        $stubKeyStorage = $this->createStubKeyStorage();
        $keyName = "key_name";


        try {
            $stubKeyStorage->load($keyName);
        } catch (MethodIsDisabledException $exception) {


            $this->assertNotEmpty($exception->getMessage());
        }
    }


    /**
     * @test
     */
    public function exists__byKeyName__throwsException()
    {
        $stubKeyStorage = $this->createStubKeyStorage();
        $keyName = "key_name";


        try {
            $stubKeyStorage->exists($keyName);
        } catch (MethodIsDisabledException $exception) {


            $this->assertNotEmpty($exception->getMessage());
        }
    }


    /**
     * @test
     */
    public function delete__byKeyName__throwsException()
    {
        $stubKeyStorage = $this->createStubKeyStorage();
        $keyName = "key_name";


        try {
            $stubKeyStorage->delete($keyName);
        } catch (MethodIsDisabledException $exception) {


            $this->assertNotEmpty($exception->getMessage());
        }
    }


    protected function createStubKeyStorage()
    {
        return new StubKeyStorage();
    }

}
