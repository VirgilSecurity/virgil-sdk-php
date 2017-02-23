<?php
namespace Virgil\Sdk\Tests\Unit\Api\Keys;


use Virgil\Sdk\Api\Keys\KeysManager;
use Virgil\Sdk\Api\Keys\KeysManagerInterface;

use Virgil\Sdk\Api\Storage\KeyEntry;

use Virgil\Sdk\Api\Keys\VirgilKey;

use Virgil\Sdk\Contracts\CryptoInterface;
use Virgil\Sdk\Contracts\KeyPairInterface;
use Virgil\Sdk\Contracts\PrivateKeyInterface;
use Virgil\Sdk\Contracts\PublicKeyInterface;

use Virgil\Sdk\Tests\Unit\Api\AbstractVirgilApiContextTest;
use Virgil\Sdk\Tests\Unit\Api\Storage\MemoryKeyStorage;

abstract class AbstractKeysManagerTest extends AbstractVirgilApiContextTest
{
    /** @var KeysManagerInterface */
    protected $keysManager;


    public function setUp()
    {
        parent::setUp();

        $this->keysManager = $this->createKeysManager();

        foreach ($this->getStoredKeys() as $keyEntryData) {
            $this->keyStorage->store($this->createKeyEntry(...$keyEntryData));
        }
    }


    /**
     * @return MemoryKeyStorage
     */
    protected function createKeyStorage()
    {
        return new MemoryKeyStorage();
    }


    /**
     * @return CryptoInterface
     */
    protected function createCrypto()
    {
        return $this->createMock(CryptoInterface::class);
    }


    /**
     * @return KeysManager
     */
    protected function createKeysManager()
    {
        return new KeysManager($this->virgilApiContext);
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
     * @param PrivateKeyInterface $privateKey
     *
     * @return VirgilKey
     */
    protected function createVirgilKey(PrivateKeyInterface $privateKey)
    {
        return new VirgilKey($this->virgilApiContext, $privateKey);
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
