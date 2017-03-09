<?php
namespace Virgil\Sdk\Tests\Unit\Api\Keys;


use PHPUnit_Framework_MockObject_MockObject;

use Virgil\Sdk\Api\Keys\KeysManager;
use Virgil\Sdk\Api\Keys\KeysManagerInterface;

use Virgil\Sdk\Api\Storage\KeyEntry;

use Virgil\Sdk\Contracts\KeyPairInterface;
use Virgil\Sdk\Contracts\PrivateKeyInterface;
use Virgil\Sdk\Contracts\PublicKeyInterface;

use Virgil\Sdk\Tests\Unit\Api\Storage\MemoryKeyStorage;

abstract class AbstractKeysManagerTest extends AbstractVirgilKeyTest
{
    /** @var KeysManagerInterface */
    protected $keysManager;

    /** @var MemoryKeyStorage */
    protected $keyStorage;

    /** @var PHPUnit_Framework_MockObject_MockObject */
    protected $crypto;


    public function setUp()
    {
        parent::setUp();

        foreach ($this->getStoredKeys() as $keyEntryData) {
            $this->keyStorage->store($this->createKeyEntry(...$keyEntryData));
        }

        $this->keysManager = $this->getKeysManager();
    }


    /**
     * @return KeysManager
     */
    protected function getKeysManager()
    {
        return new KeysManager($this->crypto, $this->keyStorage);
    }


    /**
     * @param $keyName
     * @param $keyContent
     *
     * @return KeyEntry
     */
    protected function createKeyEntry($keyName, $keyContent)
    {
        return new KeyEntry($keyName, $keyContent);
    }


    /**
     * @param PublicKeyInterface  $publicKey
     * @param PrivateKeyInterface $privateKey
     *
     * @return KeyPairInterface
     */
    protected function createCryptoKeyPair(PublicKeyInterface $publicKey, PrivateKeyInterface $privateKey)
    {
        $keyPair = $this->createMock(KeyPairInterface::class);

        $keyPair->expects($this->any())
                ->method('getPublicKey')
                ->willReturn($publicKey)
        ;

        $keyPair->expects($this->any())
                ->method('getPrivateKey')
                ->willReturn($privateKey)
        ;

        return $keyPair;
    }


    /**
     * @return array
     */
    abstract protected function getStoredKeys();
}
