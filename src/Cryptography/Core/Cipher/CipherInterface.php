<?php
namespace Virgil\Sdk\Cryptography\Core\Cipher;


use Virgil\Sdk\Cryptography\Core\Exceptions\CipherException;

/**
 * Interface provides cipher operations.
 */
interface CipherInterface
{

    /**
     * Encrypts input content by cipher.
     *
     * @param InputOutputInterface $cipherInputOutput
     * @param bool                 $embedContentInfo
     *
     * @return mixed
     *
     * @throws CipherException
     */
    public function encrypt(InputOutputInterface $cipherInputOutput, $embedContentInfo = true);


    /**
     * Decrypts encrypted content with private key.
     *
     * @param InputOutputInterface $cipherInputOutput
     * @param string               $recipientId
     * @param string               $privateKey
     *
     * @return mixed
     *
     * @throws CipherException
     */
    public function decryptWithKey(InputOutputInterface $cipherInputOutput, $recipientId, $privateKey);


    /**
     * Add recipient's public key to the cipher.
     *
     * @param string $recipientId
     * @param string $publicKey
     *
     * @return $this
     */
    public function addKeyRecipient($recipientId, $publicKey);


    /**
     * Gets data from cipher custom params.
     *
     * @param string $key
     *
     * @return string
     */
    public function getCustomParam($key);


    /**
     * Sets data to cipher custom params.
     *
     * @param string $key
     * @param string $value
     *
     * @return $this
     */
    public function setCustomParam($key, $value);


    /**
     * Creates proper cipher input output object.
     *
     * @param array ...$args
     *
     * @return InputOutputInterface
     */
    public function createInputOutput(...$args);
}
