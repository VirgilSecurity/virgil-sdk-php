<?php
namespace Virgil\Sdk\Api;


use Virgil\Sdk\Api\Storage\InvalidKeyNameException;

use Virgil\Sdk\Contracts\BufferInterface;


/**
 * Interface provides cryptographic operations where private key is mandatory.
 */
interface VirgilKeyInterface
{
    /**
     * Exports the virgil key to default format, specified in Crypto API.
     *
     * @param string $password
     *
     * @return BufferInterface
     */
    public function export($password);


    /**
     * Exports the Public key value from current virgil key.
     *
     * @return BufferInterface
     */
    public function exportPublicKey();


    /**
     * Generates a digital signature for specified content using current virgil key.
     *
     * @param string $content
     *
     * @return BufferInterface
     */
    public function sign($content);


    /**
     * Decrypts the specified cipher content using virgil key.
     *
     * @param BufferInterface $encryptedContent
     *
     * @return BufferInterface
     */
    public function decrypt(BufferInterface $encryptedContent);


    /**
     * @param mixed                 $content
     * @param VirgilCardInterface[] $recipientsVirgilCard
     *
     * @return BufferInterface
     */
    public function signThenEncrypt($content, array $recipientsVirgilCard);


    /**
     * Decrypts and verifies the content.
     *
     * @param BufferInterface     $encryptedAndSignedContent
     * @param VirgilCardInterface $signerPublicKey
     *
     * @return BufferInterface
     */
    public function decryptThenVerify(BufferInterface $encryptedAndSignedContent, VirgilCardInterface $signerPublicKey);


    /**
     * Saves a current virgil key in storage.
     *
     * @param string $keyName
     * @param string $password
     *
     * @throws InvalidKeyNameException
     *
     * @return $this
     */
    public function save($keyName, $password);
}
