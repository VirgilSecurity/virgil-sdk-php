<?php

namespace Virgil\Sdk\Cryptography\Core\Cipher;


use Exception;

use Virgil\Crypto\VirgilChunkCipher;
use Virgil\Sdk\Cryptography\Core\Exceptions\CipherException;

class VirgilStreamCipher extends AbstractVirgilCipher
{
    /**
     * Class constructor.
     *
     * @param VirgilChunkCipher $cipher
     */
    public function __construct(VirgilChunkCipher $cipher)
    {
        $this->cipher = $cipher;
    }


    /**
     * @inheritdoc
     *
     * @throws CipherException
     */
    public function encrypt(CipherInputOutputInterface $cipherInputOutput, $embedContentInfo = true)
    {
        try {
            $this->cipher->encrypt($cipherInputOutput->getInput(), $cipherInputOutput->getOutput(), $embedContentInfo);
        } catch (Exception $exception) {
            throw new CipherException($exception->getMessage(), $exception->getCode());
        }
    }


    /**
     * @inheritdoc
     *
     * @throws CipherException
     */
    public function decryptWithKey(CipherInputOutputInterface $cipherInputOutput, $recipientId, $privateKey)
    {
        try {
            $this->cipher->decryptWithKey(
                $cipherInputOutput->getInput(),
                $cipherInputOutput->getOutput(),
                $recipientId,
                $privateKey
            );
        } catch (Exception $exception) {
            throw new CipherException($exception->getMessage(), $exception->getCode());
        }
    }


    /**
     * @inheritdoc
     */
    public function createInputOutput(...$args)
    {
        return new StreamCipherInputOutput($args[0], $args[1]);
    }
}
