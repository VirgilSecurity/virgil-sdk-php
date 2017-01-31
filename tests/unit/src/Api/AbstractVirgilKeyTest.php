<?php
namespace Virgil\Sdk\Tests\Unit\Api;


use PHPUnit\Framework\TestCase;

use Virgil\Sdk\Api\VirgilApiContext;
use Virgil\Sdk\Api\VirgilApiContextInterface;
use Virgil\Sdk\Api\VirgilCardInterface;
use Virgil\Sdk\Api\VirgilKey;

use Virgil\Sdk\Contracts\CryptoInterface;
use Virgil\Sdk\Contracts\KeyStorageInterface;
use Virgil\Sdk\Contracts\PrivateKeyInterface;

use Virgil\Sdk\Contracts\PublicKeyInterface;
use Virgil\Sdk\Tests\Unit\Api\Storage\MemoryKeyStorage;

abstract class AbstractVirgilKeyTest extends TestCase
{
    /** @var MemoryKeyStorage */
    protected $keyStorage;

    /** @var CryptoInterface */
    protected $crypto;

    /** @var VirgilApiContextInterface */
    protected $virgilApiContext;


    public function setUp()
    {
        $this->keyStorage = $this->createKeyStorage();
        $this->crypto = $this->createCrypto();
        $this->virgilApiContext = $this->createVirgilApiContext($this->keyStorage, $this->crypto);
    }


    protected function createCrypto()
    {
        return $this->createMock(CryptoInterface::class);
    }


    protected function createKeyStorage()
    {
        return new MemoryKeyStorage();
    }


    protected function createVirgilApiContext(KeyStorageInterface $keyStorage, CryptoInterface $crypto)
    {
        return new VirgilApiContext($keyStorage, $crypto);
    }


    protected function createVirgilKey(PrivateKeyInterface $privateKey)
    {
        return new VirgilKey($this->virgilApiContext, $privateKey);
    }


    protected function createVirgilCard(PublicKeyInterface $publicKey)
    {
        $virgilCard = $this->createMock(VirgilCardInterface::class);

        $virgilCard->expects($this->any())
                   ->method('getPublicKey')
                   ->willReturn($publicKey)
        ;

        return $virgilCard;
    }
}
