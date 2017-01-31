<?php
namespace Virgil\Sdk\Api;


use Virgil\Sdk\Api\Storage\InvalidKeyNameException;
use Virgil\Sdk\Api\Storage\KeyEntry;

use Virgil\Sdk\Contracts\BufferInterface;
use Virgil\Sdk\Contracts\PrivateKeyInterface;

/**
 * Class represents a user's high-level Private key which provides a list of methods that allows to store the key and
 * perform cryptographic operations like Decrypt, Sign etc.
 */
class VirgilKey implements VirgilKeyInterface
{
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
        $this->privateKey = $privateKey;
    }


    /**
     * @inheritdoc
     */
    public function export($password)
    {
        // TODO: Implement export() method.
    }


    /**
     * @inheritdoc
     */
    public function exportPublicKey()
    {
        // TODO: Implement exportPublicKey() method.
    }


    /**
     * @inheritdoc
     */
    public function sign($content)
    {
        // TODO: Implement sign() method.
    }


    /**
     * @inheritdoc
     */
    public function decrypt(BufferInterface $encryptedContent)
    {
        // TODO: Implement decrypt() method.
    }


    /**
     * @inheritdoc
     */
    public function signThenEncrypt($content, array $recipientsVirgilCard)
    {
        // TODO: Implement signThenEncrypt() method.
    }


    /**
     * @inheritdoc
     */
    public function decryptThenVerify(BufferInterface $encryptedAndSignedContent, VirgilCardInterface $signerPublicKey)
    {
        // TODO: Implement decryptThenVerify() method.
    }


    /**
     * @inheritdoc
     */
    public function save($keyName, $password)
    {
        if (!preg_match('/^\w+$/', $keyName)) {
            throw new InvalidKeyNameException('Only alphanumeric names are allowed to save private key');
        }

        $crypto = $this->context->getCrypto();
        $keyStorage = $this->context->getKeyStorage();

        $exportedPrivetKeyBuffer = $crypto->exportPrivateKey($this->privateKey, $password);

        $keyEntry = new KeyEntry($keyName, $exportedPrivetKeyBuffer->getData());

        $keyStorage->store($keyEntry);

        return $this;
    }
}
