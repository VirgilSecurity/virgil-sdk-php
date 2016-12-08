<?php

namespace Virgil\Sdk\Cryptography\Core\Cipher;


use Exception;
use Virgil\Crypto\VirgilCipher as InternalVirgilCipher;
use Virgil\Sdk\Cryptography\Core\Exceptions\CipherException;

/**
 * Class implements cipher operations with primitive data (like strings, numbers etc.)
 */
class VirgilCipher extends AbstractVirgilCipher
{
    /**
     * Class constructor.
     *
     * @param InternalVirgilCipher $cipher
     */
    public function __construct(InternalVirgilCipher $cipher)
    {
        $this->cipher = $cipher;
    }


    /**
     * @inheritdoc
     *
     * @throws CipherException
     */
    public function encrypt(InputOutputInterface $cipherInputOutput, $embedContentInfo = true)
    {
        try {
            return $this->cipher->encrypt($cipherInputOutput->getInput(), $embedContentInfo);
        } catch (Exception $exception) {
            throw new CipherException($exception->getMessage(), $exception->getCode());
        }
    }


    /**
     * @inheritdoc
     *
     * @throws CipherException
     */
    public function decryptWithKey(InputOutputInterface $cipherInputOutput, $recipientId, $privateKey)
    {
        try {
            return $this->cipher->decryptWithKey($cipherInputOutput->getInput(), $recipientId, $privateKey);
        } catch (Exception $exception) {
            throw new CipherException($exception->getMessage(), $exception->getCode());
        }
    }


    /**
     * @inheritdoc
     */
    public function createInputOutput(...$args)
    {
        return new InputOutput($args[0]);
    }
}
