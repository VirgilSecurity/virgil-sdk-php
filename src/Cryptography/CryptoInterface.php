<?php

namespace Virgil\SDK\Cryptography;


interface CryptoInterface
{
    /**
     * Generate key pair by given crypto type
     *
     * @param integer $cryptoType
     * @return KeyPairInterface
     */
    public function generateKeys($cryptoType);

    /**
     * Encrypt data with a list of recipients
     *
     * @param $data
     * @param KeyInterface[] $recipients
     * @return string
     */
    public function encrypt($data, $recipients);

    /**
     * Decrypt encrypted data by given private key
     *
     * @param $encryptedData
     * @param KeyInterface $privateKey
     * @return string
     */
    public function decrypt($encryptedData, KeyInterface $privateKey);

    /**
     * Encrypt source stream to sin stream with a list of recipients
     *
     * @param $source
     * @param $sin
     * @param KeyInterface[] $recipients
     */
    public function streamEncrypt($source, $sin, $recipients);

    /**
     * Decrypt encrypted source stream to sin stream by given private key
     *
     * @param $source
     * @param $sin
     * @param KeyInterface $privateKey
     */
    public function streamDecrypt($source, $sin, KeyInterface $privateKey);
}