<?php
namespace Virgil\Sdk\Tests\Unit\Api\Cards\Identity;


use Virgil\Sdk\Tests\BaseTestCase;

use Virgil\Sdk\Client\VirgilClientInterface;

use Virgil\Sdk\Client\Requests\Constants\IdentityTypes;

use Virgil\Sdk\Api\Cards\Identity\EmailConfirmation;
use Virgil\Sdk\Api\Cards\Identity\IdentityValidationToken;
use Virgil\Sdk\Api\Cards\Identity\IdentityVerificationAttempt;

class IdentityVerificationAttemptTest extends BaseTestCase
{
    /**
     * @dataProvider confirmIdentityVerificationDataProvider
     *
     * @param array $confirmWithEmailConfirmationArgs
     *
     * @test
     */
    public function confirm__withEmailConfirmation__returnsIdentityValidationToken(
        array $confirmWithEmailConfirmationArgs
    ) {

        list($identityVerificationAttempt, $emailConfirmationMock, $expectedIdentityValidationToken) = $this->create(
            ...$confirmWithEmailConfirmationArgs
        );


        $identityValidationToken = $identityVerificationAttempt->confirm($emailConfirmationMock);


        $this->assertEquals($expectedIdentityValidationToken, $identityValidationToken);
    }


    public function confirmIdentityVerificationDataProvider()
    {
        return [
            [
                [
                    '202b65f1-ee1c-4cc2-941a-9548c9cded1c',
                    3600,
                    1,
                    IdentityTypes::TYPE_EMAIL,
                    'user@virgilsecurity.com',
                    'MIGZMA0GCWCGSAFlAwQCAgUABIGHMIGEAkB0RVkqJ89UlvsbBDgA2nPNVEhRptbF8ZVFXrZGbzSmLU9OLw2A',
                ],
            ],
        ];
    }


    protected function create(
        $actionId,
        $timeToLive,
        $countToLive,
        $identityType,
        $identity,
        $validationToken
    ) {
        $virgilClient = $this->createVirgilClient();

        $identityVerificationAttempt = $this->createIdentityVerificationAttempt(
            $virgilClient,
            $actionId,
            $timeToLive,
            $countToLive,
            $identityType,
            $identity
        );

        $emailConfirmationMock = $this->createEmailConfirmation(
            $virgilClient,
            $validationToken,
            $identityVerificationAttempt
        );

        $identityValidationToken = $this->createIdentityValidationToken(
            $virgilClient,
            $validationToken,
            $identity,
            $identityType
        );

        return [
            $identityVerificationAttempt,
            $emailConfirmationMock,
            $identityValidationToken,
        ];
    }


    protected function createIdentityVerificationAttempt(
        $virgilClient,
        $actionId,
        $timeToLive,
        $countToLive,
        $identityType,
        $identity
    ) {
        return new IdentityVerificationAttempt(
            $virgilClient, $actionId, $timeToLive, $countToLive, $identityType, $identity
        );
    }


    protected function createVirgilClient()
    {
        return $this->createMock(VirgilClientInterface::class);
    }


    protected function createEmailConfirmation($virgilClient, $expectedValidationToken, $identityVerificationAttempt)
    {
        $emailConfirmationMock = $this->createMock(EmailConfirmation::class);

        $emailConfirmationMock->expects($this->once())
                              ->method('confirmIdentity')
                              ->with($identityVerificationAttempt, $virgilClient)
                              ->willReturn($expectedValidationToken)
        ;

        return $emailConfirmationMock;
    }


    protected function createIdentityValidationToken(
        $virgilClient,
        $token,
        $identity,
        $identityType
    ) {
        return new IdentityValidationToken($virgilClient, $token, $identity, $identityType);
    }

}
