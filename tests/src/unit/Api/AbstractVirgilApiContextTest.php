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
    abstract protected function createKeyStorage();


    /**
     * @return CryptoInterface
     */
    abstract protected function createCrypto();


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

        return $virgilApiContext;
    }

}
