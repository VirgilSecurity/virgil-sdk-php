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

    /**
     * Calculate fingerprint by given content
     *
     * @param $content
     * @return string
     */
    public function calculateFingerprint($content);

    /**
     * Calculate signature for content by given private key
     *
     * @param string $content
     * @param KeyInterface $privateKey
     * @return string
     */
    public function sign($content, KeyInterface $privateKey);

    /**
     * Verify signed content by given signature and signer public key value
     *
     * @param string $content
     * @param string $signature
     * @param KeyInterface $publicKey
     * @return bool
     */
    public function verify($content, $signature, KeyInterface $publicKey);

    /**
     * Calculate signature for streamed content by given private key
     *
     * @param resource $source
     * @param KeyInterface $privateKey
     * @return string
     */
    public function streamSign($source, KeyInterface $privateKey);

    /**
     * Verify signed streamed content by given signature and signer public key value
     *
     * @param resource $source
     * @param string $signature
     * @param KeyInterface $publicKey
     * @return bool
     */
    public function streamVerify($source, $signature, KeyInterface $publicKey);

    /**
     * Extract public key instance from private key
     *
     * @param KeyInterface $privateKey
     * @return KeyInterface
     */
    public function extractPublicKey(KeyInterface $privateKey);

    /**
     * Export public key to material representation
     *
     * @param KeyInterface $publicKey
     * @return string
     */
    public function exportPublicKey(KeyInterface $publicKey);

    /**
     * Export private key to material representation
     *
     * @param KeyInterface $privateKey
     * @param string $password
     * @return string
     */
    public function exportPrivateKey(KeyInterface $privateKey, $password = '');

    /**
     * Imports the Private key from material representation
     *
     * @param string $privateKeyDERvalue
     * @param string $password
     * @return KeyInterface
     */
    public function importPrivateKey($privateKeyDERvalue, $password = '');

    /**
     * Imports the Public key from material representation
     *
     * @param string $exportedKey
     * @return KeyInterface
     */
    public function importPublicKey($exportedKey);
}