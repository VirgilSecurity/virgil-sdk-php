<?php
namespace Virgil\Sdk\Client\VirgilServices\VirgilCards\Model;


use JsonSerializable;

use Virgil\Sdk\Client\VirgilServices\Constants\JsonProperties;

/**
 * Class provides search request model for Virgil Cards Service.
 */
class SearchRequestModel implements JsonSerializable
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
     * Specify data which should be serialized to JSON
     * @link  http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        $data = [
            JsonProperties::IDENTITIES_ATTRIBUTE_NAME => $this->identities,
        ];

        if ($this->identityType != null) {
            $data[JsonProperties::IDENTITY_TYPE_ATTRIBUTE_NAME] = $this->identityType;
        }

        if ($this->scope != null) {
            $data[JsonProperties::SCOPE_ATTRIBUTE_NAME] = $this->scope;
        }

        return $data;
    }
}
