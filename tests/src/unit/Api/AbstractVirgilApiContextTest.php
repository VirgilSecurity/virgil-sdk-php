<?php
namespace Virgil\Sdk\Tests\Unit\Api;


use PHPUnit\Framework\TestCase;

use Virgil\Sdk\Api\VirgilApiContext;
use Virgil\Sdk\Api\VirgilApiContextInterface;

use Virgil\Sdk\Contracts\CryptoInterface;
use Virgil\Sdk\Contracts\KeyStorageInterface;

use Virgil\Sdk\Tests\Unit\Api\Asserts\KeysStorageAsserts;

abstract class AbstractVirgilApiContextTest extends TestCase
{
    use KeysStorageAsserts;

    /** @var KeyStorageInterface */
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


    /**
     * @return KeyStorageInterface
     */
    protected function createKeyStorage()
    {
        return $this->createMock(KeyStorageInterface::class);
    }


    /**
     * @return CryptoInterface
     */
    protected function createCrypto()
    {
        return $this->createMock(CryptoInterface::class);
    }


    /**
     * @param KeyStorageInterface $keyStorage
     * @param CryptoInterface     $crypto
     *
     * @return VirgilApiContext
     */
    protected function createVirgilApiContext(KeyStorageInterface $keyStorage, CryptoInterface $crypto)
    {
        $virgilApiContext = new VirgilApiContext();
        $virgilApiContext->setKeyStorage($keyStorage);
        $virgilApiContext->setCrypto($crypto);
        $virgilApiContext->useBuiltInVerifiers(false);

        return $virgilApiContext;
    }

}
