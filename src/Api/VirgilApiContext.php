<?php
namespace Virgil\Sdk\Api;


use Virgil\Sdk\Api\Storage\StubKeyStorage;

use Virgil\Sdk\Contracts\CryptoInterface;
use Virgil\Sdk\Contracts\KeyStorageInterface;

use Virgil\Sdk\Cryptography\VirgilCrypto;

/**
 * Class manages the virgil api common dependencies during run time.
 * It also contains a list of properties that uses to configure the high-level components.
 */
class VirgilApiContext implements VirgilApiContextInterface
{
    const AccessToken = 'access_token';
    const CardVerifiers = 'card_verifiers';
    const UseBuiltInVerifiers = 'builtin_card_verifiers_enable';
    const Credentials = 'credentials';
    const KeysPath = 'keys_path';

    /** @var string */
    private $keysPath;

    /** @var bool $useBuiltInVerifiers */
    private $useBuiltInVerifiers = true;

    /** @var KeyStorageInterface */
    private $keyStorage;

    /** @var CryptoInterface */
    private $crypto;

    /** @var string */
    private $accessToken;

    /** @var CredentialsInterface */
    private $credentials;

    /** @var array $cardVerifiers */
    private $cardVerifiers = [];


    /**
     * Class constructor.
     *
     * @param string $accessToken
     */
    public function __construct($accessToken = '')
    {
        $this->accessToken = $accessToken;

        $this->crypto = new VirgilCrypto();
        $this->keyStorage = new StubKeyStorage();
    }


    /**
     * Creates a virgil api context from config.
     *
     * @param array $config
     *
     * @return $this
     */
    public static function create(array $config)
    {
        $virgilApiContext = new self();

        if (array_key_exists(self::AccessToken, $config)) {
            $virgilApiContext->accessToken = $config[self::AccessToken];
        }

        if (array_key_exists(self::CardVerifiers, $config)) {
            $virgilApiContext->cardVerifiers = $config[self::CardVerifiers];
        }

        if (array_key_exists(self::Credentials, $config)) {
            $virgilApiContext->credentials = $config[self::Credentials];
        }

        if (array_key_exists(self::UseBuiltInVerifiers, $config)) {
            $virgilApiContext->useBuiltInVerifiers = $config[self::UseBuiltInVerifiers];
        }

        if (array_key_exists(self::KeysPath, $config)) {
            $virgilApiContext->keysPath = $config[self::KeysPath];
        }

        return $virgilApiContext;
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
    public function getAccessToken()
    {
        return $this->accessToken;
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
    public function getCardVerifiers()
    {
        return $this->cardVerifiers;
    }


    /**
     * @inheritdoc
     */
    public function isUseBuiltInVerifiers()
    {
        return $this->useBuiltInVerifiers;
    }


    /**
     * @inheritdoc
     */
    public function getKeysPath()
    {
        return $this->keysPath;
    }
}
