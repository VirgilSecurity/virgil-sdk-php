<?php
namespace Virgil\Sdk\Tests\Unit\Api\Cards;


class VirgilCardCheckIdentityTest extends AbstractVirgilCardTest
{
    /*
     * @test
     */
    public function checkIdentity__withoutOptions__returnsIdentityVerificationAttempt()
    {
        $identity = 'user@virgilsecurity.com';
        $identityType = 'email';
        $timeToLive = 3600;
        $countToLive = 1;
        $actionId = '202b65f1-ee1c-4cc2-941a-9548c9cded1c';
        $extraFields = [];

        list($card, $virgilClient) = $this->create(
            $identity,
            $identityType,
            $extraFields,
            $actionId
        );

        $expectedIdentityVerificationAttempt = Identity::createIdentityVerificationAttempt(
            $virgilClient,
            $actionId,
            $timeToLive,
            $countToLive,
            $identityType,
            $identity
        );

        $virgilCard = $this->createVirgilCard($card);


        $identityVerificationAttempt = $virgilCard->checkIdentity();


        $this->assertEquals($expectedIdentityVerificationAttempt, $identityVerificationAttempt);
    }


    /**
     * @test
     */
    public function checkIdentity__withExplicitOptions__returnsIdentityVerificationAttempt()
    {
        $identity = 'user@virgilsecurity.com';
        $identityType = 'email';
        $timeToLive = 10;
        $countToLive = 8;
        $actionId = '202b65f1-ee1c-4cc2-941a-9548c9cded1c';
        $extraFields = ['extra_field-1' => 'extra_value'];

        list($card, $virgilClient) = $this->create(
            $identity,
            $identityType,
            $extraFields,
            $actionId
        );

        $expectedIdentityVerificationAttempt = Identity::createIdentityVerificationAttempt(
            $virgilClient,
            $actionId,
            $timeToLive,
            $countToLive,
            $identityType,
            $identity
        );

        $virgilCard = $this->createVirgilCard($card);

        $identityVerificationOptions = Identity::createIdentityVerificationOptions(
            $extraFields,
            $countToLive,
            $timeToLive
        );


        $identityVerificationAttempt = $virgilCard->checkIdentity($identityVerificationOptions);


        $this->assertEquals($expectedIdentityVerificationAttempt, $identityVerificationAttempt);
    }


    protected function create($identity, $identityType, $extraFields, $actionId)
    {
        $cardMock = parent::createCard();

        $cardMock->expects($this->any())
                 ->method('getIdentity')
                 ->willReturn($identity)
        ;

        $cardMock->expects($this->any())
                 ->method('getIdentityType')
                 ->willReturn($identityType)
        ;

        $this->virgilClient->expects($this->any())
                           ->method('verifyIdentity')
                           ->with($identity, $identityType, $extraFields)
                           ->willReturn($actionId)
        ;

        return [$cardMock, $this->virgilClient];
    }
}
