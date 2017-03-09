<?php
namespace Virgil\Sdk\Tests\Unit\Api\Keys;


use Virgil\Sdk\Buffer;

use Virgil\Sdk\Contracts\PrivateKeyInterface;

class VirgilKeySaveTest extends AbstractVirgilKeyTest
{
    /**
     * @param $validPrivateKeyName
     *
     * @dataProvider validPrivateKeyNamesDataProvider
     *
     * @test
     */
    public function save__withValidNameAndPassword__savesKeyIntoStorage($validPrivateKeyName)
    {
        $privateKeyPassword = 'testPassword';
        $exportedPrivateKeyBuffer = new Buffer('exported_private_key');
        $privateKeyMock = $this->createPrivateKey();

        $this->crypto->expects($this->once())
                     ->method('exportPrivateKey')
                     ->with($privateKeyMock, $privateKeyPassword)
                     ->willReturn($exportedPrivateKeyBuffer)
        ;


        $virgilKey = $this->createVirgilKey($privateKeyMock);


        $virgilKey->save($validPrivateKeyName, $privateKeyPassword);


        $this->assertKeyInStorage($validPrivateKeyName);
    }


    /**
     * @expectedException \Virgil\Sdk\Api\Storage\InvalidKeyNameException
     *
     * @param $invalidPrivateKeyName
     *
     * @dataProvider invalidPrivateKeyNamesDataProvider
     *
     * @test
     */
    public function save__withInvalidName__throwsException($invalidPrivateKeyName)
    {
        $privateKeyPassword = 'testPassword';
        $privateKeyMock = $this->createPrivateKey();

        $virgilKey = $this->createVirgilKey($privateKeyMock);


        $virgilKey->save($invalidPrivateKeyName, $privateKeyPassword);


        //expected exception
    }


    public function validPrivateKeyNamesDataProvider()
    {
        // only alphanumeric chars are allowed
        return [
            ['validName'],
            ['valid_name'],
            ['1valid_name'],
        ];
    }


    public function invalidPrivateKeyNamesDataProvider()
    {
        // only alphanumeric chars are allowed
        return [
            ['invalid/name'], // / - invalid char
            ['invalid.name'], //  . - invalid char
            ['invalidname*'] //   * - invalid char
        ];
    }
}
