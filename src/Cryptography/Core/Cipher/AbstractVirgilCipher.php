<?php

namespace Virgil\Sdk\Cryptography\Core\Cipher;


use Virgil\Crypto\VirgilCipherBase;
use Virgil\Sdk\Cryptography\Core\Exceptions\CipherException;

/**
 * Base abstract class for ciphers.
 */
abstract class AbstractVirgilCipher implements CipherInterface
{
    /** @var VirgilCipherBase $cipher */
    protected $cipher;


    /**
     * @inheritdoc
     */
    abstract public function encrypt(InputOutputInterface $cipherInputOutput, $embedContentInfo = true);


    /**
     * @inheritdoc
     */
    abstract public function decryptWithKey(InputOutputInterface $cipherInputOutput, $recipientId, $privateKey);


    /**
     * @inheritdoc
     */
    abstract public function createInputOutput(...$args);


    /**
     * @inheritdoc
     *
     * @throws CipherException
     */
    public function addKeyRecipient($recipientId, $publicKey)
    {
        try {
            return $this->cipher->addKeyRecipient($recipientId, $publicKey);
        } catch (\Exception $e) {
            throw new CipherException($e->getMessage(), $e->getCode());
        }
    }


    /**
     * @inheritdoc
     */
    public function getCustomParam($key)
    {
        return $this->cipher->customParams()->getData($key);
    }


    /**
     * @inheritdoc
     */
    public function setCustomParam($key, $value)
    {
        $this->cipher->customParams()->setData($key, $value);

        return $this;
    }
}
