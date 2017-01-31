<?php
namespace Virgil\Sdk\Api;


use Virgil\Sdk\Api\Storage\InvalidKeyNameException;
use Virgil\Sdk\Api\Storage\KeyEntry;

use Virgil\Sdk\Contracts\BufferInterface;
use Virgil\Sdk\Contracts\CryptoInterface;
use Virgil\Sdk\Contracts\KeyStorageInterface;
use Virgil\Sdk\Contracts\PrivateKeyInterface;

/**
 * Class represents a user's high-level Private key which provides a list of methods that allows to store the key and
 * perform cryptographic operations like Decrypt, Sign etc.
 */
class VirgilKey implements VirgilKeyInterface
{
    /** @var CryptoInterface */
    private $crypto;

    /** @var KeyStorageInterface */
    private $keyStorage;

    /** @var VirgilApiContextInterface */
    private $context;

    /** @var PrivateKeyInterface */
    private $privateKey;


    /**
     * Class constructor.
     *
     * @param VirgilApiContextInterface $context
     * @param PrivateKeyInterface       $privateKey
     */
    public function __construct(VirgilApiContextInterface $context, PrivateKeyInterface $privateKey)
    {
        $this->context = $context;
        $this->crypto = $context->getCrypto();
        $this->keyStorage = $context->getKeyStorage();
        $this->privateKey = $privateKey;
    }


    /**
     * @inheritdoc
     */
    public function export($password = '')
    {
        return $this->crypto->exportPrivateKey($this->privateKey, $password);
    }


    /**
     * @inheritdoc
     */
    public function exportPublicKey()
    {
        $extractedPublicKey = $this->crypto->extractPublicKey($this->privateKey);

        return $this->crypto->exportPublicKey($extractedPublicKey);
    }


    /**
     * @inheritdoc
     */
    public function sign($content)
    {
        return $this->crypto->sign($content, $this->privateKey);
    }


    /**
     * @inheritdoc
     */
    public function decrypt(BufferInterface $encryptedContent)
    {
        return $this->crypto->decrypt($encryptedContent, $this->privateKey);
    }


    /**
     * @inheritdoc
     */
    public function signThenEncrypt($content, array $recipientsVirgilCard)
    {
        $virgilCardToPublicKey = function (VirgilCardInterface $virgilCard) {
            return $virgilCard->getPublicKey();
        };

        $recipientsPublicKeys = array_map($virgilCardToPublicKey, $recipientsVirgilCard);

        return $this->crypto->signThenEncrypt($content, $this->privateKey, $recipientsPublicKeys);
    }


    /**
     * @inheritdoc
     */
    public function decryptThenVerify(BufferInterface $encryptedAndSignedContent, VirgilCardInterface $signerPublicKey)
    {
        return $this->crypto->decryptThenVerify(
            $encryptedAndSignedContent,
            $this->privateKey,
            $signerPublicKey->getPublicKey()
        );
    }


    /**
     * @inheritdoc
     */
    public function save($keyName, $password)
    {
        if (!preg_match('/^\w+$/', $keyName)) {
            throw new InvalidKeyNameException('Only alphanumeric names are allowed to save private key');
        }

        $exportedPrivetKeyBuffer = $this->crypto->exportPrivateKey($this->privateKey, $password);

        $keyEntry = new KeyEntry($keyName, $exportedPrivetKeyBuffer->getData());

        $this->keyStorage->store($keyEntry);

        return $this;
    }
}
