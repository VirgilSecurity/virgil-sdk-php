<?php
namespace Virgil\Sdk\Client\Requests;


use Virgil\Sdk\Client\VirgilCards\SearchCriteria;

/**
 * Class aims to build search criteria.
 */
class SearchCardRequest
{
    /** @var null|string */
    private $identityType;

    /** @var null|string */
    private $scope;

    /** @var array $identities */
    private $identities = [];


    /**
     * Class constructor.
     *
     * @param string|null $identityType
     * @param string|null $scope
     */
    public function __construct($identityType = null, $scope = null)
    {
        $this->identityType = $identityType;
        $this->scope = $scope;
    }


    /**
     * Sets search criteria identities.
     *
     * @param array $identities
     */
    public function setIdentities(array $identities)
    {
        $this->identities = $identities;
    }


    /**
     * Appends search criteria identity.
     *
     * @param $identity
     *
     * @return $this
     */
    public function appendIdentity($identity)
    {
        $this->identities[] = $identity;

        return $this;
    }


    /**
     * Returns search criteria.
     *
     * @return SearchCriteria
     *
     * @throws SearchCardRequestException
     */
    public function getSearchCriteria()
    {
        if (count($this->identities) === 0) {
            throw new SearchCardRequestException(
                'Cant build search criteria with empty identities. Identities are mandatory attribute.'
            );
        }

        return new SearchCriteria(array_unique($this->identities), $this->identityType, $this->scope);
    }

}
