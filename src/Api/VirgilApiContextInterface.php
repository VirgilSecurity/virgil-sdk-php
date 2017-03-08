<?php
namespace Virgil\Sdk\Api;


use Virgil\Sdk\Client\VirgilClientInterface;

use Virgil\Sdk\Client\Validator\CardVerifierInfoInterface;

use Virgil\Sdk\Client\Requests\RequestSignerInterface;

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
     * Gets a Virgil Security services client.
     *
     * @return VirgilClientInterface
     */
    public function getClient();


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
     * Sets a Virgil Security services client.
     *
     * @param VirgilClientInterface $virgilClient
     *
     * @return $this
     */
    public function setClient(VirgilClientInterface $virgilClient);


    /**
     * Gets application access token.
     *
     * @return string
     */
    public function getAccessToken();


    /**
     * Sets application access token.
     *
     * @param string $accessToken
     *
     * @return $this
     */
    public function setAccessToken($accessToken);


    /**
     * Sets the application authentication credentials.
     *
     * @param CredentialsInterface $credentials
     *
     * @return $this
     */
    public function setCredentials(CredentialsInterface $credentials);


    /**
     * Gets the application authentication credentials.
     *
     * @return CredentialsInterface
     */
    public function getCredentials();


    /**
     * Gets the card request signer.
     *
     * @return RequestSignerInterface
     */
    public function getRequestSigner();


    /**
     * Sets the card request signer.
     *
     * @param RequestSignerInterface $requestSigner
     *
     * @return $this
     */
    public function setRequestSigner(RequestSignerInterface $requestSigner);


    /**
     * Appends additional card verifier.
     *
     * @param CardVerifierInfoInterface $cardVerifierInfo
     *
     * @return $this
     */
    public function appendCardVerifier(CardVerifierInfoInterface $cardVerifierInfo);


    /**
     * If sets true than built in verifiers should be enabled.
     *
     * @param bool $flag
     *
     * @return $this
     */
    public function useBuiltInVerifiers($flag);
}
