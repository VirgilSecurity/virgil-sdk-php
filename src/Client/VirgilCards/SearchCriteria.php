<?php
namespace Virgil\Sdk\Client\VirgilCards;


/**
 * Class provides search criteria for Virgil Cards Service.
 */
class SearchCriteria
{
    /** @var array $identities */
    private $identities;

    /** @var null|string $identityType */
    private $identityType;

    /** @var null|string $scope */
    private $scope;


    /**
     * Class constructor.
     *
     * @param array  $identities   specifies list of identities.
     * @param string $identityType specifies identity type.
     * @param string $scope        specifies card scope.
     */
    public function __construct(array $identities, $identityType = null, $scope = null)
    {
        $this->identities = $identities;
        $this->identityType = $identityType;
        $this->scope = $scope;
    }


    /**
     * Returns identities to search.
     *
     * @return array
     */
    public function getIdentities()
    {
        return $this->identities;
    }


    /**
     * Returns identity type.
     *
     * @return null|string
     */
    public function getIdentityType()
    {
        return $this->identityType;
    }


    /**
     * Returns search scope.
     *
     * @return null|string
     */
    public function getScope()
    {
        return $this->scope;
    }
}
