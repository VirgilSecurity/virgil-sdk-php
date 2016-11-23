<?php

namespace Virgil\SDK\Client\Card\Model;

/**
 * Class SearchCriteria
 *
 * TODO: add search criteria builder for convenient usage.
 * SearchQuery::builder().identity('identity1').identity('identity2).identityType('email').scope('global').build()
 * @package Virgil\SDK\Client\Model
 */
class SearchCriteria
{
    private $identities;
    private $identityType;
    private $scope;

    /**
     * SearchCriteria constructor.
     *
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
}