<?php
namespace Virgil\Sdk\Api;


use Virgil\Sdk\Contracts\BufferInterface;
use Virgil\Sdk\Contracts\CryptoInterface;

/**
 * Class provides credentials for application authentication using AppID and AppKey retrieved from development
 * dashboard.
 */
class AppCredentials implements CredentialsInterface
{
    /** @var string */
    private $appId;

    /** @var BufferInterface */
    private $appKey;

    /** @var string */
    private $appKeyPassword;


    /**
     * Class constructor.
     *
     * @param string          $appId
     * @param BufferInterface $appKey
     * @param string          $appKeyPassword
     */
    public function __construct($appId, BufferInterface $appKey, $appKeyPassword = '')
    {
        $this->appId = $appId;
        $this->appKey = $appKey;
        $this->appKeyPassword = $appKeyPassword;
    }


    /**
     * @inheritdoc
     */
    public function getAppKey(CryptoInterface $crypto)
    {
        return $crypto->importPrivateKey($this->appKey, $this->appKeyPassword);
    }


    /**
     * @inheritdoc
     */
    public function getAppId()
    {
        return $this->appId;
    }
}
