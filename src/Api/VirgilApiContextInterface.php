<?php
namespace Virgil\Sdk\Api;


use Virgil\Sdk\Client\Validator\CardVerifierInfoInterface;

use Virgil\Sdk\Contracts\CryptoInterface;
use Virgil\Sdk\Contracts\KeyStorageInterface;

/**
 * Interface provides virgil api dependencies.
 */
interface VirgilApiContextInterface
{
    /**
     * Gets a cryptographic keys storage.
     *
     * @return KeyStorageInterface
     */
    public function getKeyStorage();


    /**
     * Gets a crypto API that represents a set of methods for dealing with low-level.
     *
     * @return CryptoInterface
     */
    public function getCrypto();


    /**
     * Sets the custom cryptographic keys storage.
     *
     * @param KeyStorageInterface $keyStorage
     *
     * @return $this
     */
    public function setKeyStorage(KeyStorageInterface $keyStorage);


    /**
     * Sets the custom crypto API that represents a set of methods for dealing with low-level.
     *
     * @param CryptoInterface $crypto
     *
     * @return $this
     */
    public function setCrypto(CryptoInterface $crypto);


    /**
     * Gets application access token.
     *
     * @return string
     */
    public function getAccessToken();


    /**
     * Gets the application authentication credentials.
     *
     * @return CredentialsInterface
     */
    public function getCredentials();


    /**
     * Gets a list of card verifiers.
     *
     * @return CardVerifierInfoInterface[]
     */
    public function getCardVerifiers();


    /**
     * Indicates if builtin card verifiers is in use.
     *
     * @return bool
     */
    public function isUseBuiltInVerifiers();


    /**
     * Gets a path where virgil keys store.
     *
     * @return string
     */
    public function getKeysPath();


    /**
     * Gets a key pair type.
     *
     * @return string
     */
    public function getKeyPairType();
}
