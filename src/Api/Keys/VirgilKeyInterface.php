<?php
namespace Virgil\Sdk\Api\Keys;


use Virgil\Sdk\Api\Cards\VirgilCardInterface;
use Virgil\Sdk\Api\Cards\VirgilCardsInterface;

use Virgil\Sdk\Api\Storage\InvalidKeyNameException;

use Virgil\Sdk\Contracts\BufferInterface;
use Virgil\Sdk\Contracts\PrivateKeyInterface;


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
     * @param mixed $content
     *
     * @return BufferInterface
     */
    public function sign($content);


    /**
     * Decrypts the specified cipher content using virgil key.
     *
     * @param mixed $encryptedContent base64 encoded string or Buffer
     *
     * @return BufferInterface
     */
    public function decrypt($encryptedContent);


    /**
     * Signs then encrypt content for virgil cards.
     *
     * @param mixed                $content
     * @param VirgilCardsInterface $recipientsVirgilCards
     *
     * @return BufferInterface
     */
    public function signThenEncrypt($content, VirgilCardsInterface $recipientsVirgilCards);


    /**
     * Decrypts and verifies the content.
     *
     * @param mixed               $encryptedAndSignedContent base64 encoded string or Buffer
     * @param VirgilCardInterface $signerPublicKey
     *
     * @return BufferInterface
     */
    public function decryptThenVerify($encryptedAndSignedContent, VirgilCardInterface $signerPublicKey);


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


    /**
     * Gets a private key.
     *
     * @return PrivateKeyInterface
     */
    public function getPrivateKey();
}
