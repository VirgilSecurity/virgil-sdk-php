<?php
namespace Virgil\Sdk\Api;


use Virgil\Sdk\Api\Storage\StubKeyStorage;
use Virgil\Sdk\Contracts\CryptoInterface;
use Virgil\Sdk\Contracts\KeyStorageInterface;
use Virgil\Sdk\Cryptography\VirgilCrypto;

/**
 * Class manages the virgil api dependencies during run time.
 * It also contains a list of properties that uses to configure the high-level components.
 */
class VirgilApiContext implements VirgilApiContextInterface
{
    /** @var KeyStorageInterface */
    private $keyStorage;

    /** @var CryptoInterface */
    private $crypto;


    /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->keyStorage = new StubKeyStorage();
        $this->crypto = new VirgilCrypto();
    }


    /**
     * @inheritdoc
     */
    public function getKeyStorage()
    {
        return $this->keyStorage;
    }


    /**
     * @inheritdoc
     */
    public function getCrypto()
    {
        return $this->crypto;
    }


    /**
     * @inheritdoc
     */
    public function setKeyStorage(KeyStorageInterface $keyStorage)
    {
        $this->keyStorage = $keyStorage;
    }


    /**
     * @inheritdoc
     */
    public function setCrypto(CryptoInterface $crypto)
    {
        $this->crypto = $crypto;
    }
}
