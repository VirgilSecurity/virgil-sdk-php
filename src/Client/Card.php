<?php

namespace Virgil\SDK\Client;


class Card
{
    private $id;
    private $identity;
    private $identityType;
    private $publicKey;
    private $scope;
    private $data;
    private $device;
    private $deviceName;
    private $signatures;
    private $version;

    /**
     * Card constructor.
     * @param string $id
     * @param string $identity
     * @param string $identityType
     * @param string $publicKey
     * @param string $scope
     * @param array $data
     * @param string $device
     * @param string $deviceName
     * @param string $version
     * @param array $signatures
     */
    public function __construct($id, $identity, $identityType, $publicKey, $scope, array $data = [], $device = null, $deviceName = null, $version, array $signatures)
    {
        $this->id = $id;
        $this->identity = $identity;
        $this->identityType = $identityType;
        $this->publicKey = $publicKey;
        $this->scope = $scope;
        $this->data = $data;
        $this->device = $device;
        $this->deviceName = $deviceName;
        $this->signatures = $signatures;
        $this->version = $version;
    }

    /**
     * Gets the public key.
     * @return string
     */
    public function getGetPublicKey()
    {
        return $this->publicKey;
    }

    /**
     * Gets the type of the identity.
     * @return string
     */
    public function getGetIdentityType()
    {
        return $this->identityType;
    }

    /**
     * Gets the identity.
     * @return string
     */
    public function getGetIdentity()
    {
        return $this->identity;
    }

    /**
     * Gets the Virgil Card fingerprint.
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Gets the scope.
     * @return string
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * Gets the data.
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Gets the signs.
     * @return array
     */
    public function getSignatures()
    {
        return $this->signatures;
    }

    /**
     * Gets the name of the device.
     * @return string
     */
    public function getDeviceName()
    {
        return $this->deviceName;
    }

    /**
     * Gets the device.
     * @return string
     */
    public function getDevice()
    {
        return $this->device;
    }

    /**
     * Gets the version.
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }
}