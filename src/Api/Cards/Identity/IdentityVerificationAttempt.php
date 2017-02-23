<?php
namespace Virgil\Sdk\Api\Cards\Identity;


use Virgil\Sdk\Client\VirgilClientInterface;


/**
 * Class provides identity verification with given identity confirmation strategy.
 */
class IdentityVerificationAttempt implements IdentityVerificationAttemptInterface
{
    /** @var string */
    private $actionId;

    /** @var int */
    private $timeToLive;

    /** @var int */
    private $countToLive;

    /** @var string */
    private $identityType;

    /** @var string */
    private $identity;

    /** @var VirgilClientInterface */
    private $client;


    /**
     * Class constructor.
     *
     * @param VirgilClientInterface $virgilClient
     * @param string                $actionId
     * @param int                   $timeToLive
     * @param int                   $countToLive
     * @param string                $identityType
     * @param string                $identity
     */
    public function __construct(
        VirgilClientInterface $virgilClient,
        $actionId,
        $timeToLive,
        $countToLive,
        $identityType,
        $identity
    ) {
        $this->client = $virgilClient;
        $this->actionId = $actionId;
        $this->timeToLive = $timeToLive;
        $this->countToLive = $countToLive;
        $this->identityType = $identityType;
        $this->identity = $identity;
    }


    /**
     * @inheritdoc
     */
    public function getActionId()
    {
        return $this->actionId;
    }


    /**
     * @inheritdoc
     */
    public function getIdentity()
    {
        return $this->identity;
    }


    /**
     * @inheritdoc
     */
    public function getIdentityType()
    {
        return $this->identityType;
    }


    /**
     * @inheritdoc
     */
    public function getTimeToLive()
    {
        return $this->timeToLive;
    }


    /**
     * @inheritdoc
     */
    public function getCountToLive()
    {
        return $this->countToLive;
    }


    /**
     * @inheritdoc
     */
    public function confirm(IdentityConfirmationInterface $identityConfirmation)
    {
        $identityValidationToken = $identityConfirmation->confirmIdentity($this, $this->client);

        return new IdentityValidationToken(
            $this->client, $identityValidationToken, $this->identity, $this->identityType
        );
    }
}
