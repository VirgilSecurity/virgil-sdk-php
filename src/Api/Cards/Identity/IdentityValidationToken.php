<?php
namespace Virgil\Sdk\Api\Cards\Identity;


use Virgil\Sdk\Client\VirgilClientInterface;

/**
 * Class provides information about validation token.
 */
class IdentityValidationToken implements IdentityValidationTokenInterface
{
    /** @var string */
    private $token;

    /** @var VirgilClientInterface */
    private $virgilClient;

    /** @var string */
    private $identity;

    /** @var string */
    private $identityType;


    /**
     * Class constructor.
     *
     * @param VirgilClientInterface $virgilClient
     * @param string                $token
     * @param string                $identity
     * @param string                $identityType
     */
    public function __construct(VirgilClientInterface $virgilClient, $token, $identity, $identityType)
    {
        $this->token = $token;
        $this->virgilClient = $virgilClient;
        $this->identity = $identity;
        $this->identityType = $identityType;
    }


    /**
     * @inheritdoc
     */
    public function getToken()
    {
        return $this->token;
    }


    /**
     * @inheritdoc
     */
    public function isValid()
    {
        return $this->virgilClient->isIdentityValid($this->identity, $this->identityType, $this->token);
    }
}
