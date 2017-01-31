<?php
namespace Virgil\Sdk\Tests\Unit\Api;


use PHPUnit\Framework\TestCase;

use Virgil\Sdk\Buffer;

use Virgil\Sdk\Api\VirgilKey;
use Virgil\Sdk\Api\VirgilApiContext;
use Virgil\Sdk\Api\VirgilApiContextInterface;

use Virgil\Sdk\Api\Storage\InvalidKeyNameException;

use Virgil\Sdk\Contracts\CryptoInterface;
use Virgil\Sdk\Contracts\KeyStorageInterface;
use Virgil\Sdk\Contracts\PrivateKeyInterface;

use Virgil\Sdk\Tests\Unit\Api\Storage\MemoryKeyStorage;

class VirgilKeySaveTest extends TestCase
{
    /** @var MemoryKeyStorage */
    protected $keyStorage;

    /** @var CryptoInterface */
    protected $crypto;

    /** @var VirgilApiContextInterface */
    protected $virgilApiContext;


    public function setUp()
    {
        $this->keyStorage = new MemoryKeyStorage();
        $this->crypto = $this->createMock(CryptoInterface::class);
        $this->virgilApiContext = $this->createVirgilApiContext($this->keyStorage, $this->crypto);
    }


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


    protected function createVirgilApiContext(KeyStorageInterface $keyStorage, CryptoInterface $crypto)
    {
        return new VirgilApiContext($keyStorage, $crypto);
    }


    protected function createVirgilKey(PrivateKeyInterface $privateKey)
    {
        return new VirgilKey($this->virgilApiContext, $privateKey);
    }


    protected function assertKeyInStorage($privateKeyName, $isMatches = true)
    {
        $this->assertEquals($isMatches, $this->keyStorage->exists($privateKeyName));
    }
}
