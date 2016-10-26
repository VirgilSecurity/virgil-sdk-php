<?php

namespace Virgil\SDK\Cryptography\CryptoAPI;


use Virgil\SDK\Cryptography\CryptoAPI\Cipher\CipherInterface;

interface CryptoAPI
{
    /**
     * Generate public/private key
     *
     * @param integer $type Key generation type
     * @return KeyPair
     */
    public function generate($type);

    /**
     * Converts private key to DER format
     *
     * @param string $key
     * @return string
     */
    public function privateKeyToDER($key);

    /**
     * Converts public key to DER format
     *
     * @param string $key
     * @return string
     */
    public function publicKeyToDER($key);

    /**
     * Compares key pair
     *
     * @param string $publicKey
     * @param string $privateKey
     * @return string
     */
    public function isKeyPairMatch($publicKey, $privateKey);

    /**
     * Calculates key hash
     *
     * @param string $key DER public key value
     * @param integer $algorithm Hash algorithm
     * @return string
     */
    public function computeKeyHash($key, $algorithm);

    /**
     * Extracts public key from a private key
     *
     * @param string $privateKey
     * @param string $password
     * @return string
     */
    public function extractPublicKey($privateKey, $password);

    /**
     * Encrypts private key with a password
     *
     * @param string $privateKey
     * @param string $password
     * @return string
     */
    public function encryptPrivateKey($privateKey, $password);

    /**
     * Decrypts private key with a password
     *
     * @param string $privateKey
     * @param string $password
     * @return string
     */
    public function decryptPrivateKey($privateKey, $password);

    /**
     * Sign data with a private key
     *
     * @param string $data
     * @param string $privateKey
     * @return string
     */
    public function sign($data, $privateKey);

    /**
     * Sign stream with a private key
     *
     * @param resource $stream
     * @param string $privateKey
     * @return string
     */
    public function streamSign($stream, $privateKey);

    /**
     * Verify data with a public key
     *
     * @param string $data
     * @param string $signature
     * @param string $publicKey
     * @return bool
     */
    public function verify($data, $signature, $publicKey);

    /**
     * Verify stream with a public key
     *
     * @param resource $stream
     * @param string $signature
     * @param string $publicKey
     * @return bool
     */
    public function streamVerify($stream, $signature, $publicKey);

    /**
     * Gets cipher for encrypt\decrypt data
     *
     * @return CipherInterface
     */
    public function cipher();

    /**
     * Gets cipher for encrypt\decrypt streams of data
     *
     * @return CipherInterface
     */
    public function streamCipher();
}