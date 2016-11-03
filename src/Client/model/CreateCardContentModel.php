<?php

namespace Virgil\SDK\Client\Model;


use Virgil\SDK\AbstractJsonSerializable;

class CreateCardContentModel extends AbstractJsonSerializable
{
    private $identity;
    private $identityType;
    private $publicKey;
    private $data;
    private $scope;
    private $info;

    /**
     * CardContent constructor.
     * @param string $identity
     * @param string $identityType
     * @param string $publicKey
     * @param array $data
     * @param string $scope
     * @param DeviceInfoModel $info
     */
    public function __construct($identity, $identityType, $publicKey, $scope, array $data = null, DeviceInfoModel $info = null)
    {
        $this->identity = $identity;
        $this->identityType = $identityType;
        $this->publicKey = $publicKey;
        $this->data = $data;
        $this->scope = $scope;
        $this->info = $info;
    }

    /**
     * @return string
     */
    public function getIdentity()
    {
        return $this->identity;
    }

    /**
     * @return string
     */
    public function getIdentityType()
    {
        return $this->identityType;
    }

    /**
     * @return string
     */
    public function getPublicKey()
    {
        return $this->publicKey;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return string
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * @return DeviceInfoModel
     */
    public function getInfo()
    {
        return $this->info;
    }

    function jsonSerialize()
    {
        return array_filter([
            'identity' => $this->identity,
            'identity_type' => $this->identityType,
            'public_key' => $this->publicKey,
            'data' => $this->data,
            'scope' => $this->scope,
            'info' => $this->info,
        ], function ($value) {
            return count($value) !== 0;
        });
    }
}