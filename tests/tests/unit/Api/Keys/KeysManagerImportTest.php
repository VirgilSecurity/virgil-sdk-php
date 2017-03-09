<?php
namespace Virgil\Sdk\Tests\Unit\Api\Keys;


use Virgil\Sdk\Buffer;

class KeysManagerImportTest extends AbstractKeysManagerTest
{
    /**
     * @test
     */
    public function import__exportedVirgilKeyWithPassword__returnsVirgilKey()
    {
        $privateKey = $this->createPrivateKey();
        $virgilKeyBuffer = new Buffer('virgil key buffer');
        $password = 'qwerty';

        $expectedVirgilKey = $this->createVirgilKey($privateKey);

        $this->crypto->expects($this->once())
                     ->method('importPrivateKey')
                     ->with($virgilKeyBuffer, $password)
                     ->willReturn($privateKey)
        ;


        $virgilKey = $this->keysManager->import($virgilKeyBuffer, $password);


        $this->assertEquals($expectedVirgilKey, $virgilKey);
    }


    /**
     * @test
     */
    public function import__exportedVirgilKey__returnsVirgilKey()
    {
        $privateKey = $this->createPrivateKey();
        $virgilKeyBuffer = new Buffer('virgil key buffer');

        $expectedVirgilKey = $this->createVirgilKey($privateKey);

        $this->crypto->expects($this->once())
                     ->method('importPrivateKey')
                     ->with($virgilKeyBuffer)
                     ->willReturn($privateKey)
        ;


        $virgilKey = $this->keysManager->import($virgilKeyBuffer);


        $this->assertEquals($expectedVirgilKey, $virgilKey);
    }


    /**
     * @return array
     */
    protected function getStoredKeys()
    {
        return [];
    }
}
