<?php
namespace Virgil\Sdk\Contracts;


/**
 * Cryptographic interface provides a set of methods for
 * dealing with low-level cryptographic primitives and algorithms.
 */
interface CryptoInterface
{
    /**
     * Generates the public\private key pair.
     *
     * @return KeyPairInterface
     */
    public function generateKeys();


    /**
     * Encrypts the content with a list of recipients public keys.
     *
     * @param mixed                $content
     * @param PublicKeyInterface[] $recipientsPublicKeys is a list of recipients public keys
     *
     * @return BufferInterface
     */
    public function encrypt($content, array $recipientsPublicKeys);


    /**
     * Decrypts encrypted content by given recipient private key.
     *
     * @param BufferInterface     $encryptedContent
     * @param PrivateKeyInterface $recipientPrivateKey is the recipient private key
     *
     * @return BufferInterface
     */
    public function decrypt(BufferInterface $encryptedContent, PrivateKeyInterface $recipientPrivateKey);


    /**
     * Encrypts source stream to sin stream with a list of recipients.
     *
     * @param resource             $source               is input stream
     * @param resource             $sin                  is output stream
     * @param PublicKeyInterface[] $recipientsPublicKeys is a list of recipients public keys
     */
    public function encryptStream($source, $sin, array $recipientsPublicKeys);


    /**
     * Decrypts encrypted source stream to sin stream by given recipient private key.
     *
     * @param resource            $source              is input stream
     * @param resource            $sin                 is output stream
     * @param PrivateKeyInterface $recipientPrivateKey is the recipient private key
     */
    public function decryptStream($source, $sin, PrivateKeyInterface $recipientPrivateKey);


    /**
     * Calculates the fingerprint from given content.
     *
     * @param mixed $content
     *
     * @return BufferInterface
     */
    public function calculateFingerprint($content);


    /**
     * Signs the content by given signer private key.
     *
     * @param mixed               $content
     * @param PrivateKeyInterface $signerPrivateKey is a signer private key
     *
     * @return BufferInterface returns signature
     */
    public function sign($content, PrivateKeyInterface $signerPrivateKey);


    /**
     * Verifies signed content by given signature and signer public key.
     *
     * @param mixed              $content
     * @param BufferInterface    $signature
     * @param PublicKeyInterface $signerPublicKey is a signer public key
     *
     * @return bool
     */
    public function verify($content, BufferInterface $signature, PublicKeyInterface $signerPublicKey);


    /**
     * Signs the content stream by given signer private key.
     *
     * @param resource            $source
     * @param PrivateKeyInterface $signerPrivateKey is a signer private key
     *
     * @return BufferInterface returns signature
     */
    public function signStream($source, PrivateKeyInterface $signerPrivateKey);


    /**
     * Verifies signed streamed content by given signature and signer public key.
     *
     * @param resource           $source
     * @param BufferInterface    $signature
     * @param PublicKeyInterface $signerPublicKey is a signer public key
     *
     * @return bool
     */
    public function verifyStream($source, BufferInterface $signature, PublicKeyInterface $signerPublicKey);


    /**
     * Extracts the public key instance from private key.
     *
     * @param PrivateKeyInterface $privateKey
     *
     * @return PublicKeyInterface
     */
    public function extractPublicKey(PrivateKeyInterface $privateKey);


    /**
     * Exports the public key to material representation.
     *
     * @param PublicKeyInterface $publicKey
     *
     * @return BufferInterface returns public key DER encoded value
     */
    public function exportPublicKey(PublicKeyInterface $publicKey);


    /**
     * Exports the private key to material representation.
     *
     * @param PrivateKeyInterface $privateKey
     * @param string              $password
     *
     * @return BufferInterface returns private key DER encoded value
     */
    public function exportPrivateKey(PrivateKeyInterface $privateKey, $password = '');


    /**
     * Imports the Private key from material representation.
     *
     * @param BufferInterface $exportedPrivateKey DER encoded private key
     * @param string          $password
     *
     * @return PrivateKeyInterface
     */
    public function importPrivateKey(BufferInterface $exportedPrivateKey, $password = '');


    /**
     * Imports the Public key from material representation.
     *
     * @param BufferInterface $exportedPublicKey DER encoded public key
     *
     * @return PublicKeyInterface
     */
    public function importPublicKey(BufferInterface $exportedPublicKey);


    /**
     * Signs then encrypt content with a list of recipients public keys.
     *
     * @param mixed                $content
     * @param PrivateKeyInterface  $signerPrivateKey     is a signer private key
     * @param PublicKeyInterface[] $recipientsPublicKeys is a list of recipients public keys
     *
     * @return BufferInterface
     */
    public function signThenEncrypt($content, PrivateKeyInterface $signerPrivateKey, array $recipientsPublicKeys);


    /**
     * Decrypts then verify encrypted content.
     *
     * @param BufferInterface     $encryptedAndSignedContent
     * @param PrivateKeyInterface $recipientPrivateKey is a recipient private key
     * @param PublicKeyInterface  $signerPublicKey     is a signer public key
     *
     * @return BufferInterface
     */
    public function decryptThenVerify(
        BufferInterface $encryptedAndSignedContent,
        PrivateKeyInterface $recipientPrivateKey,
        PublicKeyInterface $signerPublicKey
    );
}
