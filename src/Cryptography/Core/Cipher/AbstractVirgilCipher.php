<?php

namespace Virgil\Sdk\Cryptography\Core\Cipher;


use Virgil\Crypto\VirgilCipherBase;

use Virgil\Sdk\Cryptography\Core\Exceptions\CipherException;

abstract class AbstractVirgilCipher implements CipherInterface
{
    /** @var VirgilCipherBase $cipher */
    protected $cipher;


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
