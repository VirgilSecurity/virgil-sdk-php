<?php
namespace Virgil\Sdk\Tests\Unit\Api\Cards\Identity;


use Virgil\Sdk\Client\VirgilClientInterface;

use Virgil\Sdk\Tests\BaseTestCase;

use Virgil\Sdk\Api\Cards\Identity\EmailConfirmation;
use Virgil\Sdk\Api\Cards\Identity\IdentityVerificationAttemptInterface;

class EmailConfirmationTest extends BaseTestCase
{

    /**
     * @dataProvider confirmIdentityWithIdentityVerificationAttemptDataProvider
     *
     * @param $actionId
     * @param $confirmationCode
     * @param $timeToLive
     * @param $countToLive
     *
     * @test
     */
    public function confirmIdentity__withIdentityVerificationAttempt__callsVirgilClientConfirmIdentity(
        $actionId,
        $confirmationCode,
        $timeToLive,
        $countToLive
    ) {

        $emailConfirmation = $this->createEmailConfirmation($confirmationCode);

        $virgilClientMock = $this->createVirgilClient();

        $identityVerificationAttemptMock = $this->createIdentityVerificationAttempt(
            $actionId,
            $timeToLive,
            $countToLive
        );


        $virgilClientMock->expects($this->once())
                         ->method('confirmIdentity')
                         ->with($actionId, $confirmationCode, $timeToLive, $countToLive)
        ;


        $emailConfirmation->confirmIdentity($identityVerificationAttemptMock, $virgilClientMock);
    }


    public function confirmIdentityWithIdentityVerificationAttemptDataProvider()
    {
        return [
            [
                '202b65f1-ee1c-4cc2-941a-9548c9cded1c',
                'ffagf3fff3f3',
                3600,
                1,
            ],
        ];
    }


    protected function createEmailConfirmation($confirmationCode)
    {
        return new EmailConfirmation($confirmationCode);
    }


    protected function createVirgilClient()
    {
        return $this->createMock(VirgilClientInterface::class);
    }


    protected function createIdentityVerificationAttempt($actionId, $timeToLive, $countToLive)
    {
        $identityVerificationAttemptInterface = $this->createMock(IdentityVerificationAttemptInterface::class);

        $identityVerificationAttemptInterface->expects($this->any())
                                             ->method('getActionId')
                                             ->willReturn($actionId)
        ;

        $identityVerificationAttemptInterface->expects($this->any())
                                             ->method('getTimeToLive')
                                             ->willReturn($timeToLive)
        ;

        $identityVerificationAttemptInterface->expects($this->any())
                                             ->method('getCountToLive')
                                             ->willReturn($countToLive)
        ;

        return $identityVerificationAttemptInterface;
    }

}
