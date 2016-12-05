<?php

namespace Virgil\Sdk\Cryptography\CryptoAPI\Cipher;


use Virgil\Sdk\Cryptography\CryptoAPI\Exceptions\CipherException;

class VirgilCipher extends AbstractVirgilCipher
{
    /** @var \Virgil\Crypto\VirgilCipher */
    protected $cipher;

    /**
     * Encrypt data
     *
     * @param string $data
     * @param bool $embedContentInfo
     * @return string
     * @throws CipherException
     */
    public function encrypt($data, $embedContentInfo = true)
    {
        try {
            return $this->cipher->encrypt($data, $embedContentInfo);
        } catch (\Exception $e) {
            throw new CipherException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Decrypt encrypted data with private key
     *
     * @param string $encryptedData
     * @param string $recipientId
     * @param string $privateKey
     * @return string
     * @throws CipherException
     */
    public function decryptWithKey($encryptedData, $recipientId, $privateKey)
    {
        try {
            return $this->cipher->decryptWithKey($encryptedData, $recipientId, $privateKey);
        } catch (\Exception $e) {
            throw new CipherException($e->getMessage(), $e->getCode());
        }
    }
}
