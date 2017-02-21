<?php
namespace Virgil\Sdk\Api\Manager;


use Virgil\Sdk\Buffer;

use Virgil\Sdk\Api\VirgilApiContextInterface;
use Virgil\Sdk\Api\VirgilKey;

use Virgil\Sdk\Contracts\CryptoInterface;
use Virgil\Sdk\Contracts\KeyStorageInterface;


/**
 * Class provides a list of methods to generate the virgil keys and manage them.
 */
class KeysManager implements KeysManagerInterface
{
    /** @var VirgilApiContextInterface */
    private $context;

    /** @var CryptoInterface */
    private $crypto;

    /** @var KeyStorageInterface */
    private $keyStorage;


    /**
     * Class constructor.
     *
     * @param VirgilApiContextInterface $virgilApiContext
     */
    public function __construct(VirgilApiContextInterface $virgilApiContext)
    {
        $this->context = $virgilApiContext;
        $this->crypto = $virgilApiContext->getCrypto();
        $this->keyStorage = $virgilApiContext->getKeyStorage();
    }


    /**
     * @inheritdoc
     */
    public function generate()
    {
        $cryptoKeyPair = $this->crypto->generateKeys();

        return new VirgilKey($this->context, $cryptoKeyPair->getPrivateKey());
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

        return new VirgilKey($this->context, $importedPrivateKey);
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
}
