<?php

namespace Virgil\SDK\Client;


use Virgil\SDK\AbstractJsonSerializable;

class SearchCriteria extends AbstractJsonSerializable
{
    private $identities;
    private $identityType;
    private $scope;

    /**
     * SearchCriteria constructor.
     * @param array $identities
     * @param string $identityType
     * @param string $scope
     */
    public function __construct(array $identities, $identityType = null, $scope = null)
    {
        $this->identities = $identities;
        $this->identityType = $identityType;
        $this->scope = $scope;
    }

    /**
     * @return array
     */
    public function getIdentities()
    {
        return $this->identities;
    }

    /**
     * @return null|string
     */
    public function getIdentityType()
    {
        return $this->identityType;
    }

    /**
     * @return null|string
     */
    public function getScope()
    {
        return $this->scope;
    }

    function jsonSerialize()
    {
        return array_filter([
            'identities' => $this->identities,
            'identity_type' => $this->identityType,
            'scope' => $this->scope
        ], function ($value) {
            return count($value) !== 0;
        });
    }
}