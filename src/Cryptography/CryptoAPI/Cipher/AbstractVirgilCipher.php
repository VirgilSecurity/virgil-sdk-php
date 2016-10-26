<?php

namespace Virgil\SDK\Cryptography\CryptoAPI\Cipher;


use Virgil\Crypto\VirgilCipherBase;
use Virgil\SDK\Cryptography\CryptoAPI\Exceptions\CipherException;

abstract class AbstractVirgilCipher implements CipherInterface
{
    protected $cipher;

    /**
     * AbstractVirgilCipher constructor.
     * @param VirgilCipherBase $cipher
     */
    public function __construct(VirgilCipherBase $cipher)
    {
        $this->cipher = $cipher;
    }

    /**
     * @inheritdoc
     * @throws CipherException
     */
    public function addKeyRecipient($receiverId, $publicKey)
    {
        try {
            return $this->cipher->addKeyRecipient($receiverId, $publicKey);
        } catch (\Exception $e) {
            throw new CipherException($e->getMessage(), $e->getCode());
        }
    }
}