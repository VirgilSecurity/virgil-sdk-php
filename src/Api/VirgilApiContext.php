<?php
namespace Virgil\Sdk\Api;


use Virgil\Sdk\Api\Storage\StubKeyStorage;

use Virgil\Sdk\Client\VirgilClient;
use Virgil\Sdk\Client\VirgilClientInterface;

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

    /** @var VirgilClientInterface */
    private $client;


    /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->keyStorage = new StubKeyStorage();
        $this->crypto = new VirgilCrypto();
        $this->client = VirgilClient::create();
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

        return $this;
    }


    /**
     * @inheritdoc
     */
    public function setCrypto(CryptoInterface $crypto)
    {
        $this->crypto = $crypto;

        return $this;
    }


    /**
     * @inheritdoc
     */
    public function getClient()
    {
        return $this->client;
    }


    /**
     * @inheritdoc
     */
    public function setClient(VirgilClientInterface $virgilClient)
    {
        $this->client = $virgilClient;

        return $this;
    }
}
