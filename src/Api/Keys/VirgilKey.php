<?php
namespace Virgil\Sdk\Api\Keys;


use Virgil\Sdk\Buffer;

use Virgil\Sdk\Api\Cards\VirgilCardInterface;

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

    /** @var PrivateKeyInterface */
    private $privateKey;


    /**
     * Class constructor.
     *
     * @param CryptoInterface     $crypto
     * @param KeyStorageInterface $keyStorage
     * @param PrivateKeyInterface $privateKey
     */
    public function __construct(
        CryptoInterface $crypto,
        KeyStorageInterface $keyStorage,
        PrivateKeyInterface $privateKey
    ) {
        $this->crypto = $crypto;
        $this->keyStorage = $keyStorage;
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
        return $this->crypto->sign((string)$content, $this->privateKey);
    }


    /**
     * @inheritdoc
     */
    public function decrypt($encryptedContent)
    {
        if (!$encryptedContent instanceof BufferInterface) {
            $encryptedContent = Buffer::fromBase64($encryptedContent);
        }

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

        return $this->crypto->signThenEncrypt((string)$content, $this->privateKey, $recipientsPublicKeys);
    }


    /**
     * @inheritdoc
     */
    public function decryptThenVerify($encryptedAndSignedContent, VirgilCardInterface $signerPublicKey)
    {
        if (!$encryptedAndSignedContent instanceof BufferInterface) {
            $encryptedAndSignedContent = Buffer::fromBase64($encryptedAndSignedContent);
        }

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


    /**
     * @inheritdoc
     */
    public function getPrivateKey()
    {
        return $this->privateKey;
    }
}
