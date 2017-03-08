<?php
namespace Virgil\Sdk\Api;


use Virgil\Sdk\Api\Storage\StubKeyStorage;

use Virgil\Sdk\Client\Requests\RequestSigner;
use Virgil\Sdk\Client\Requests\RequestSignerInterface;

use Virgil\Sdk\Client\Validator\CardValidator;
use Virgil\Sdk\Client\Validator\CardValidatorInterface;
use Virgil\Sdk\Client\Validator\CardVerifierInfoInterface;

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
    /** @var bool $useBuiltInVerifiers */
    public $useBuiltInVerifiers = true;

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

    /** @var array $cardVerifiers */
    private $cardVerifiers = [];


    /**
     * Class constructor.
     *
     * @param string $accessToken
     */
    public function __construct($accessToken = null)
    {
        $this->accessToken = $accessToken;
    }


    /**
     * @inheritdoc
     */
    public function getKeyStorage()
    {
        if ($this->keyStorage === null) {
            $this->keyStorage = new StubKeyStorage();
        }

        return $this->keyStorage;
    }


    /**
     * @inheritdoc
     */
    public function getCrypto()
    {
        if ($this->crypto === null) {
            $this->crypto = new VirgilCrypto();
        }

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

        $this->requestSigner = null;
        $this->client = null;

        return $this;
    }


    /**
     * @inheritdoc
     */
    public function getClient()
    {
        if ($this->client === null) {
            $cardValidator = $this->getCardValidator();

            $this->client = $this->initClient($this->accessToken, $cardValidator);
        }

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

        $this->client = null;

        return $this;
    }


    /**
     * @inheritdoc
     */
    public function setCredentials(CredentialsInterface $credentials)
    {
        $this->credentials = $credentials;

        return $this;
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
        if ($this->requestSigner === null) {
            $this->requestSigner = $this->initRequestSigner($this->crypto);
        }

        return $this->requestSigner;
    }


    /**
     * @inheritdoc
     */
    public function setRequestSigner(RequestSignerInterface $requestSigner)
    {
        $this->requestSigner = $requestSigner;

        return $this;
    }


    /**
     * @inheritdoc
     */
    public function appendCardVerifier(CardVerifierInfoInterface $cardVerifierInfo)
    {
        $this->cardVerifiers[] = $cardVerifierInfo;

        $this->client = null;

        return $this;
    }


    /**
     * @inheritdoc
     */
    public function useBuiltInVerifiers($flag)
    {
        $this->useBuiltInVerifiers = $flag;

        $this->client = null;

        return $this;
    }


    /**
     * Init the virgil client.
     *
     * @param string                 $accessToken
     * @param CardValidatorInterface $cardValidator
     *
     * @return VirgilClient
     */
    private function initClient($accessToken, CardValidatorInterface $cardValidator)
    {
        $virgilClient = VirgilClient::create($accessToken);

        $virgilClient->setCardValidator($cardValidator);

        return $virgilClient;
    }


    /**
     * Init the card validator.
     *
     * @param CryptoInterface $crypto
     * @param bool            $useBuiltInVerifiers
     * @param array           $cardVerifiers
     *
     * @return CardValidator
     */
    private function initCardValidator(CryptoInterface $crypto, $useBuiltInVerifiers = true, array $cardVerifiers = [])
    {
        $cardValidator = new CardValidator($crypto, $useBuiltInVerifiers);

        /** @var CardVerifierInfoInterface $cardVerifier */
        foreach ($cardVerifiers as $cardVerifier) {
            $verifierPublicKey = $crypto->importPublicKey($cardVerifier->getPublicKeyData());
            $cardValidator->addVerifier($cardVerifier->getCardId(), $verifierPublicKey);
        }

        return $cardValidator;
    }


    /**
     * @return CardValidator
     */
    private function getCardValidator()
    {
        return $this->initCardValidator($this->getCrypto(), $this->useBuiltInVerifiers, $this->cardVerifiers);
    }


    /**
     * Init the request signer.
     *
     * @param CryptoInterface $crypto
     *
     * @return RequestSignerInterface
     */
    private function initRequestSigner(CryptoInterface $crypto)
    {
        return new RequestSigner($crypto);
    }
}
