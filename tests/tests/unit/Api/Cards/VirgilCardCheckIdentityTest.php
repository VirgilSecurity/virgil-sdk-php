<?php
namespace Virgil\Sdk\Tests\Unit\Api\Cards;


use Virgil\Sdk\Api\VirgilApiContextInterface;

use Virgil\Sdk\Client\Card;
use Virgil\Sdk\Client\VirgilClientInterface;

use Virgil\Sdk\Tests\BaseTestCase;

use Virgil\Sdk\Tests\Unit\Api\Cards;

class VirgilCardCheckIdentityTest extends BaseTestCase
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

        list($card, $virgilApiContext) = $this->create(
            $identity,
            $identityType,
            $extraFields,
            $actionId
        );

        $expectedIdentityVerificationAttempt = Identity::createIdentityVerificationAttempt(
            $virgilApiContext->getClient(),
            $actionId,
            $timeToLive,
            $countToLive,
            $identityType,
            $identity
        );

        $virgilCard = Cards::createVirgilCard($virgilApiContext, $card);


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

        list($card, $virgilApiContext) = $this->create(
            $identity,
            $identityType,
            $extraFields,
            $actionId
        );

        $expectedIdentityVerificationAttempt = Identity::createIdentityVerificationAttempt(
            $virgilApiContext->getClient(),
            $actionId,
            $timeToLive,
            $countToLive,
            $identityType,
            $identity
        );

        $virgilCard = Cards::createVirgilCard($virgilApiContext, $card);

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
        $cardMock = $this->createCard($identity, $identityType);

        $virgilClientMock = $this->createVirgilClient($identity, $identityType, $extraFields, $actionId);

        $virgilApiContextMock = $this->createVirgilApiContext($virgilClientMock);

        return [$cardMock, $virgilApiContextMock];
    }


    /**
     * @param $identity
     * @param $identityType
     * @param $extraFields
     * @param $actionId
     *
     * @return VirgilClientInterface
     */
    protected function createVirgilClient($identity, $identityType, $extraFields, $actionId)
    {
        $virgilClient = $this->createMock(VirgilClientInterface::class);

        $virgilClient->expects($this->any())
                     ->method('verifyIdentity')
                     ->with($identity, $identityType, $extraFields)
                     ->willReturn($actionId)
        ;

        return $virgilClient;
    }


    /**
     * @param VirgilClientInterface $virgilClient
     *
     * @return VirgilApiContextInterface
     */
    protected function createVirgilApiContext(VirgilClientInterface $virgilClient)
    {
        $virgilApiContext = $this->createMock(VirgilApiContextInterface::class);

        $virgilApiContext->expects($this->any())
                         ->method('getClient')
                         ->willReturn($virgilClient)
        ;

        return $virgilApiContext;
    }


    /**
     * @param $identity
     * @param $identityType
     *
     * @return Card
     */
    protected function createCard($identity, $identityType)
    {
        $cardMock = $this->createMock(Card::class);

        $cardMock->expects($this->any())
                 ->method('getIdentity')
                 ->willReturn($identity)
        ;

        $cardMock->expects($this->any())
                 ->method('getIdentityType')
                 ->willReturn($identityType)
        ;

        return $cardMock;
    }

}
