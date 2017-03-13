<?php
namespace Virgil\Sdk\Api;


use Virgil\Sdk\Contracts\CryptoInterface;
use Virgil\Sdk\Contracts\PrivateKeyInterface;


/**
 * Interface provides the base authentication class for retrieving credentials for the high-level API.
 */
interface CredentialsInterface
{
    /**
     * Gets the application private key used to authenticate publish/revoke Card request.
     *
     * @param CryptoInterface $crypto
     *
     * @return PrivateKeyInterface
     */
    public function getAppKey(CryptoInterface $crypto);


    /**
     * Gets the application identifier.
     *
     * @return string
     */
    public function getAppId();
}
