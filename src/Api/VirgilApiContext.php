<?php
namespace Virgil\Sdk\Api;


use Virgil\Sdk\Contracts\CryptoInterface;
use Virgil\Sdk\Contracts\KeyStorageInterface;

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
     *
     * @param KeyStorageInterface $keyStorage
     * @param CryptoInterface     $crypto
     */
    public function __construct(KeyStorageInterface $keyStorage, CryptoInterface $crypto)
    {
        $this->keyStorage = $keyStorage;
        $this->crypto = $crypto;
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
}
