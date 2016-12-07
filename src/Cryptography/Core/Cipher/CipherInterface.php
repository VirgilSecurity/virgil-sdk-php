<?php

namespace Virgil\Sdk\Cryptography\Core\Cipher;


use Virgil\Sdk\Cryptography\Core\Exceptions\CipherException;

interface CipherInterface
{

    /**
     * Encrypts input content by cipher.
     *
     * @param CipherInputOutputInterface $cipherInputOutput
     * @param bool                       $embedContentInfo
     *
     * @return mixed
     * @throws CipherException
     */
    public function encrypt(CipherInputOutputInterface $cipherInputOutput, $embedContentInfo = true);


    /**
     * Decrypts encrypted content with private key.
     *
     * @param CipherInputOutputInterface $cipherInputOutput
     * @param string                     $recipientId
     * @param string                     $privateKey
     *
     * @return mixed
     * @throws CipherException
     */
    public function decryptWithKey(CipherInputOutputInterface $cipherInputOutput, $recipientId, $privateKey);


    /**
     * Add recipient's public key to the cipher.
     *
     * @param string $recipientId
     * @param string $publicKey
     *
     * @return mixed
     *
     */
    public function addKeyRecipient($recipientId, $publicKey);


    /**
     * Gets data from cipher custom params.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function getCustomParam($key);


    /**
     * Sets data to cipher custom params.
     *
     * @param string $key
     * @param string $value
     *
     * @return CipherInterface
     */
    public function setCustomParam($key, $value);


    /**
     * Creates proper cipher input output object.
     *
     * @param array ...$args
     *
     * @return CipherInputOutputInterface
     */
    public function createInputOutput(...$args);
}
