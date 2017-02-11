<?php
namespace Virgil\Sdk\Client\Requests;


use Virgil\Sdk\Client\VirgilServices\VirgilCards\Model\SearchRequestModel;

/**
 * TODO: add import/export
 *
 * Class aims to build search request model conveniently.
 */
class SearchCardRequest implements CardRequestInterface
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
     * Sets search request model identities.
     *
     * @param array $identities
     */
    public function setIdentities(array $identities)
    {
        $this->identities = $identities;
    }


    /**
     * Appends search request model identity.
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
     * @inheritdoc
     *
     * @return SearchRequestModel
     *
     * @throws SearchCardRequestException
     */
    public function getRequestModel()
    {
        if (count($this->identities) === 0) {
            throw new SearchCardRequestException(
                'Cant build search request model with empty identities. Identities are mandatory attribute.'
            );
        }

        return new SearchRequestModel(array_unique($this->identities), $this->identityType, $this->scope);
    }
}
