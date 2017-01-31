<?php
namespace Virgil\Sdk\Tests\Unit\Api;


use Virgil\Sdk\Buffer;
use Virgil\Sdk\Contracts\PrivateKeyInterface;
use Virgil\Sdk\Api\Storage\InvalidKeyNameException;

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
        $privateKeyMock = $this->createMock(PrivateKeyInterface::class);

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
     * @param $invalidPrivateKeyName
     *
     * @dataProvider invalidPrivateKeyNamesDataProvider
     *
     * @test
     */
    public function save__withInvalidName__throwsException($invalidPrivateKeyName)
    {
        $privateKeyPassword = 'testPassword';
        $privateKeyMock = $this->createMock(PrivateKeyInterface::class);

        $virgilKey = $this->createVirgilKey($privateKeyMock);


        try {
            $virgilKey->save($invalidPrivateKeyName, $privateKeyPassword);
        } catch (InvalidKeyNameException $exception) {


            $this->assertKeyInStorage($invalidPrivateKeyName, false);
        }

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


    protected function assertKeyInStorage($privateKeyName, $isMatches = true)
    {
        $this->assertEquals($isMatches, $this->keyStorage->exists($privateKeyName));
    }
}
