<?php
namespace Virgil\Sdk\Api;


use Virgil\Sdk\Api\Storage\StubKeyStorage;

use Virgil\Sdk\Client\Requests\RequestSigner;
use Virgil\Sdk\Client\Requests\RequestSignerInterface;

use Virgil\Sdk\Client\VirgilClient;
use Virgil\Sdk\Client\VirgilClientInterface;

use Virgil\Sdk\Contracts\CryptoInterface;
use Virgil\Sdk\Contracts\KeyStorageInterface;

use Virgil\Sdk\Cryptography\VirgilCrypto;

/**
 * Class manages the virgil api dependencies during run time.
 * It also contains a list of properties that uses to configure the high-level components.
 */
class VirgilApiContext implements VirgilApiContextInterface
{
    /** @var KeyStorageInterface */
    private $keyStorage;

    /** @var CryptoInterface */
    private $crypto;

    /** @var VirgilClientInterface */
    private $client;

    /** @var string */
    private $accessToken;

    /** @var CredentialsInterface */
    private $credentials;

    /** @var RequestSignerInterface */
    private $requestSigner;


    /**
     * Class constructor.
     *
     * @param string $accessToken
     */
    public function __construct($accessToken = null)
    {
        $this->keyStorage = new StubKeyStorage();
        $this->crypto = new VirgilCrypto();
        $this->client = VirgilClient::create($accessToken);
        $this->requestSigner = new RequestSigner($this->crypto);

        $this->accessToken = $accessToken;
    }


    /**
     * @inheritdoc
     */
    public function getKeyStorage()
    {
        return $this->keyStorage;
    }


    /**
     * @inheritdoc
     */
    public function getCrypto()
    {
        return $this->crypto;
    }


    /**
     * @inheritdoc
     */
    public function setKeyStorage(KeyStorageInterface $keyStorage)
    {
        $this->keyStorage = $keyStorage;

        return $this;
    }


    /**
     * @inheritdoc
     */
    public function setCrypto(CryptoInterface $crypto)
    {
        $this->crypto = $crypto;

        return $this;
    }


    /**
     * @inheritdoc
     */
    public function getClient()
    {
        return $this->client;
    }


    /**
     * @inheritdoc
     */
    public function setClient(VirgilClientInterface $virgilClient)
    {
        $this->client = $virgilClient;

        return $this;
    }


    /**
     * @inheritdoc
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }


    /**
     * @inheritdoc
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
    }


    /**
     * @inheritdoc
     */
    public function setCredentials(CredentialsInterface $credentials)
    {
        $this->credentials = $credentials;
    }


    /**
     * @inheritdoc
     */
    public function getCredentials()
    {
        return $this->credentials;
    }


    /**
     * @inheritdoc
     */
    public function getRequestSigner()
    {
        return $this->requestSigner;
    }


    /**
     * @inheritdoc
     */
    public function setRequestSigner(RequestSignerInterface $requestSigner)
    {
        $this->requestSigner = $requestSigner;
    }
}
