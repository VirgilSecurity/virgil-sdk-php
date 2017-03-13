<?php
namespace Virgil\Sdk\Tests\Unit\Api\Keys;


use PHPUnit_Framework_MockObject_MockObject;

use Virgil\Sdk\Api\Cards\VirgilCardInterface;

use Virgil\Sdk\Api\Keys\VirgilKey;

use Virgil\Sdk\Contracts\CryptoInterface;
use Virgil\Sdk\Contracts\PrivateKeyInterface;
use Virgil\Sdk\Contracts\PublicKeyInterface;

use Virgil\Sdk\Tests\BaseTestCase;

use Virgil\Sdk\Tests\Unit\Api\Asserts\KeysStorageAsserts;
use Virgil\Sdk\Tests\Unit\Api\Storage\MemoryKeyStorage;

abstract class AbstractVirgilKeyTest extends BaseTestCase
{
    use KeysStorageAsserts;

    /** @var PHPUnit_Framework_MockObject_MockObject */
    protected $crypto;

    /** @var PHPUnit_Framework_MockObject_MockObject */
    protected $keyStorage;


    public function setUp()
    {
        $this->crypto = $this->createCrypto();
        $this->keyStorage = $this->createKeyStorage();
    }


    /**
     * @return CryptoInterface
     */
    protected function createCrypto()
    {
        return $this->createMock(CryptoInterface::class);
    }


    /**
     * @return MemoryKeyStorage
     */
    protected function createKeyStorage()
    {
        return new MemoryKeyStorage();
    }


    /**
     * @param PrivateKeyInterface $privateKey
     *
     * @return VirgilKey
     */
    protected function createVirgilKey(PrivateKeyInterface $privateKey)
    {
        return new VirgilKey($this->crypto, $this->keyStorage, $privateKey);
    }


    /**
     * @param PublicKeyInterface $publicKey
     *
     * @return VirgilCardInterface
     */
    protected function createVirgilCard(PublicKeyInterface $publicKey)
    {
        $virgilCard = $this->createMock(VirgilCardInterface::class);

        $virgilCard->expects($this->any())
                   ->method('getPublicKey')
                   ->willReturn($publicKey)
        ;

        return $virgilCard;
    }


    /**
     * @return PrivateKeyInterface
     */
    protected function createPrivateKey()
    {
        return $this->createMock(PrivateKeyInterface::class);
    }


    /**
     * @return PublicKeyInterface
     */
    protected function createPublicKey()
    {
        return $this->createMock(PublicKeyInterface::class);
    }
}
