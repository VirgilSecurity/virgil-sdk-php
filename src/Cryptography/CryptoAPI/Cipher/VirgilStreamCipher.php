<?php

namespace Virgil\SDK\Cryptography\CryptoAPI\Cipher;


use Virgil\SDK\Cryptography\CryptoAPI\Exceptions\CipherException;

class VirgilStreamCipher extends AbstractVirgilCipher
{
    /** @var \Virgil\Crypto\VirgilChunkCipher */
    protected $cipher;

    /**
     * Encrypt source stream to sink stream
     *
     * @param resource $source
     * @param resource $sink
     * @param bool $embedContentInfo
     * @throws CipherException
     */
    public function encrypt($source, $sink, $embedContentInfo = true)
    {
        try {
            $sourceStream = new VirgilStreamDataSource($source);
            $sinStream = new VirgilStreamDataSink($sink);
            $sourceStream->reset();
            $this->cipher->encrypt($sourceStream, $sinStream, $embedContentInfo);
        } catch (\Exception $e) {
            throw new CipherException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Decrypt source stream to sink stream with private key
     *
     * @param resource $source
     * @param resource $sink
     * @param string $recipientId
     * @param string $privateKey
     * @throws CipherException
     */
    public function decryptWithKey($source, $sink, $recipientId, $privateKey)
    {
        try {
            $sourceStream = new VirgilStreamDataSource($source);
            $sinStream = new VirgilStreamDataSink($sink);
            $sourceStream->reset();
            $this->cipher->decryptWithKey($sourceStream, $sinStream, $recipientId, $privateKey);
        } catch (\Exception $e) {
            throw new CipherException($e->getMessage(), $e->getCode());
        }
    }
}