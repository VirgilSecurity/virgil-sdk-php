<?php
namespace Virgil\Sdk\Api\Cards\Identity;


use Virgil\Sdk\Client\VirgilClientInterface;

/**
 * Class provides a logic to confirm the email identity.
 */
class EmailConfirmation implements IdentityConfirmationInterface
{
    /** @var string */
    private $confirmationCode;


    /**
     * Class constructor.
     *
     * @param string $confirmationCode
     */
    public function __construct($confirmationCode)
    {
        $this->confirmationCode = $confirmationCode;
    }


    /**
     * @inheritdoc
     */
    public function confirmIdentity(
        IdentityVerificationAttemptInterface $identityVerificationAttempt,
        VirgilClientInterface $virgilClient
    ) {
        return $virgilClient->confirmIdentity(
            $identityVerificationAttempt->getActionId(),
            $this->confirmationCode,
            $identityVerificationAttempt->getTimeToLive(),
            $identityVerificationAttempt->getCountToLive()
        );
    }
}
