<?php
namespace Virgil\Sdk\Client\VirgilCards\Model;


use Virgil\Sdk\Client\VirgilCards\Constants\JsonProperties;

use Virgil\Sdk\Client\VirgilServices\Model\AbstractModel;

/**
 * Class provides search request model for Virgil Cards Service.
 */
class SearchRequestModel extends AbstractModel
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


    /**
     * @inheritdoc
     */
    protected function jsonSerializeData()
    {
        return [
            JsonProperties::IDENTITIES_ATTRIBUTE_NAME    => $this->identities,
            JsonProperties::IDENTITY_TYPE_ATTRIBUTE_NAME => $this->identityType,
            JsonProperties::SCOPE_ATTRIBUTE_NAME         => $this->scope,
        ];
    }
}
