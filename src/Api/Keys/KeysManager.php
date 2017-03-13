<?php
namespace Virgil\Sdk\Api\Keys;


use Virgil\Sdk\Buffer;

use Virgil\Sdk\Contracts\BufferInterface;
use Virgil\Sdk\Contracts\CryptoInterface;
use Virgil\Sdk\Contracts\KeyStorageInterface;

/**
 * Class provides a list of methods to generate the virgil keys and manage them.
 */
class KeysManager implements KeysManagerInterface
{
    /** @var CryptoInterface */
    private $crypto;

    /** @var KeyStorageInterface */
    private $keyStorage;


    /**
     * Class constructor.
     *
     * @param CryptoInterface     $crypto
     * @param KeyStorageInterface $keyStorage
     *
     */
    public function __construct(CryptoInterface $crypto, KeyStorageInterface $keyStorage)
    {
        $this->crypto = $crypto;
        $this->keyStorage = $keyStorage;
    }


    /**
     * @inheritdoc
     */
    public function generate()
    {
        $cryptoKeyPair = $this->crypto->generateKeys();

        return new VirgilKey($this->crypto, $this->keyStorage, $cryptoKeyPair->getPrivateKey());
    }


    /**
     * @inheritdoc
     */
    public function load($keyName, $keyPassword = '')
    {
        if (!$this->keyStorage->exists($keyName)) {
            throw new VirgilKeyIsNotFoundException();
        }

        $keyEntry = $this->keyStorage->load($keyName);
        $keyValue = new Buffer($keyEntry->getValue());

        $importedPrivateKey = $this->crypto->importPrivateKey($keyValue, $keyPassword);

        return new VirgilKey($this->crypto, $this->keyStorage, $importedPrivateKey);
    }


    /**
     * @inheritdoc
     */
    public function destroy($keyName)
    {
        if (!$this->keyStorage->exists($keyName)) {
            throw new VirgilKeyIsNotFoundException();
        }

        $this->keyStorage->delete($keyName);
    }


    /**
     * @inheritdoc
     */
    public function import(BufferInterface $virgilKeyBuffer, $password = '')
    {
        $privateKey = $this->crypto->importPrivateKey($virgilKeyBuffer, $password);

        return new VirgilKey($this->crypto, $this->keyStorage, $privateKey);
    }
}
