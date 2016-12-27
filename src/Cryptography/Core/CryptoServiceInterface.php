<?php
namespace Virgil\Sdk\Cryptography\Core;


use Virgil\Sdk\Cryptography\Core\Cipher\CipherInterface;

/**
 * Interface provides low level crypto operations.
 */
interface CryptoServiceInterface
{
    /**
     * Generate public/private key pair.
     *
     * @param integer $keyPairType
     *
     * @return KeyPairInterface
     */
    public function generateKeyPair($keyPairType);


    /**
     * Converts private key to DER format.
     *
     * @param string $privateKey
     * @param string $privateKyePassword
     *
     * @return string
     */
    public function privateKeyToDer($privateKey, $privateKyePassword = '');


    /**
     * Converts public key to DER format.
     *
     * @param string $publicKey
     *
     * @return string
     */
    public function publicKeyToDer($publicKey);


    /**
     * Checks if given keys are parts of the same key pair.
     *
     * @param string $publicKey
     * @param string $privateKey
     *
     * @return bool
     */
    public function isKeyPair($publicKey, $privateKey);


    /**
     * Calculates key hash by the hash algorithm.
     *
     * @param string  $publicKey     DER public key value
     * @param integer $hashAlgorithm Hash algorithm
     *
     * @return string
     */
    public function computeHash($publicKey, $hashAlgorithm);


    /**
     * Extracts public key from a private key.
     *
     * @param string $privateKey
     * @param string $privateKeyPassword
     *
     * @return string
     */
    public function extractPublicKey($privateKey, $privateKeyPassword);


    /**
     * Encrypts private key with a password.
     *
     * @param string $privateKey
     * @param string $password
     *
     * @return string
     */
    public function encryptPrivateKey($privateKey, $password);


    /**
     * Decrypts private key with a password.
     *
     * @param string $privateKey
     * @param string $privateKeyPassword
     *
     * @return string
     */
    public function decryptPrivateKey($privateKey, $privateKeyPassword);


    /**
     * Sign content with a private key.
     *
     * @param string $content
     * @param string $privateKey
     *
     * @return string
     */
    public function sign($content, $privateKey);


    /**
     * Sign stream with a private key
     *
     * @param resource $stream
     * @param string   $privateKey
     *
     * @return string
     */
    public function signStream($stream, $privateKey);


    /**
     * Verify content with a public key and signature.
     *
     * @param string $content
     * @param string $signature
     * @param string $publicKey
     *
     * @return bool
     */
    public function verify($content, $signature, $publicKey);


    /**
     * Verify stream with a public key and signature.
     *
     * @param resource $stream
     * @param string   $signature
     * @param string   $publicKey
     *
     * @return bool
     */
    public function verifyStream($stream, $signature, $publicKey);


    /**
     * Creates cipher for encrypt\decrypt content.
     *
     * @return CipherInterface
     */
    public function createCipher();


    /**
     * Creates cipher for encrypt\decrypt content stream.
     *
     * @return CipherInterface
     */
    public function createStreamCipher();
}
