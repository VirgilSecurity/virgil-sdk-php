<?php

namespace Virgil\SDK\Contracts;


use Virgil\SDK\BufferInterface;

interface CryptoInterface
{
    /**
     * Generate key pair by given crypto type
     *
     * @return KeyPairInterface
     */
    public function generateKeys();

    /**
     * Encrypt data with a list of recipients
     *
     * @param string $data
     * @param PublicKeyInterface[] $recipients
     * @return BufferInterface
     */
    public function encrypt($data, $recipients);

    /**
     * Decrypt encrypted data by given private key
     *
     * @param BufferInterface $encryptedData
     * @param PrivateKeyInterface $privateKey
     * @return BufferInterface
     */
    public function decrypt(BufferInterface $encryptedData, PrivateKeyInterface $privateKey);

    /**
     * Encrypt source stream to sin stream with a list of recipients
     *
     * @param $source
     * @param $sin
     * @param PublicKeyInterface[] $recipients
     */
    public function streamEncrypt($source, $sin, $recipients);

    /**
     * Decrypt encrypted source stream to sin stream by given private key
     *
     * @param $source
     * @param $sin
     * @param PrivateKeyInterface $privateKey
     */
    public function streamDecrypt($source, $sin, PrivateKeyInterface $privateKey);

    /**
     * Calculate fingerprint by given content
     *
     * @param BufferInterface $content
     * @return BufferInterface
     */
    public function calculateFingerprint(BufferInterface $content);

    /**
     * Calculate signature for content by given private key
     *
     * @param string $content
     * @param PrivateKeyInterface $privateKey
     * @return BufferInterface
     */
    public function sign($content, PrivateKeyInterface $privateKey);

    /**
     * Verify signed content by given signature and signer public key value
     *
     * @param string $content
     * @param BufferInterface $signature
     * @param PublicKeyInterface $publicKey
     * @return bool
     */
    public function verify($content, BufferInterface $signature, PublicKeyInterface $publicKey);

    /**
     * Calculate signature for streamed content by given private key
     *
     * @param resource $source
     * @param PrivateKeyInterface $privateKey
     * @return BufferInterface
     */
    public function streamSign($source, PrivateKeyInterface $privateKey);

    /**
     * Verify signed streamed content by given signature and signer public key value
     *
     * @param resource $source
     * @param BufferInterface $signature
     * @param PublicKeyInterface $publicKey
     * @return bool
     */
    public function streamVerify($source, BufferInterface $signature, PublicKeyInterface $publicKey);

    /**
     * Extract public key instance from private key
     *
     * @param PrivateKeyInterface $privateKey
     * @return PublicKeyInterface
     */
    public function extractPublicKey(PrivateKeyInterface $privateKey);

    /**
     * Export public key to material representation
     *
     * @param PublicKeyInterface $publicKey
     * @return BufferInterface
     */
    public function exportPublicKey(PublicKeyInterface $publicKey);

    /**
     * Export private key to material representation
     *
     * @param PrivateKeyInterface $privateKey
     * @param string $password
     * @return BufferInterface
     */
    public function exportPrivateKey(PrivateKeyInterface $privateKey, $password = '');

    /**
     * Imports the Private key from material representation
     *
     * @param BufferInterface $privateKeyDER
     * @param string $password
     * @return PrivateKeyInterface
     */
    public function importPrivateKey(BufferInterface $privateKeyDER, $password = '');

    /**
     * Imports the Public key from material representation
     *
     * @param BufferInterface $exportedKey
     * @return PublicKeyInterface
     */
    public function importPublicKey(BufferInterface $exportedKey);

    /**
     * Sign then encrypt data with a list of recipients
     *
     * @param string $data
     * @param PrivateKeyInterface $privateKey
     * @param PublicKeyInterface[] $recipients
     * @return BufferInterface
     */
    public function signThenEncrypt($data, PrivateKeyInterface $privateKey, $recipients);

    /**
     * Decrypt then verify encrypted data
     *
     * @param BufferInterface $encryptedData
     * @param PrivateKeyInterface $privateKey
     * @param PublicKeyInterface $publicKey
     * @return BufferInterface
     */
    public function decryptThenVerify(BufferInterface $encryptedData, PrivateKeyInterface $privateKey, PublicKeyInterface $publicKey);
}