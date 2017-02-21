<?php
namespace Virgil\Sdk\Tests\Unit\Client;

use Virgil\Sdk\Tests\BaseTestCase;

use Virgil\Sdk\Client\VirgilServices\VirgilIdentity\IdentityServiceInterface;

use Virgil\Sdk\Client\VirgilClient;
use Virgil\Sdk\Client\VirgilClientParams;

use Virgil\Sdk\Client\VirgilServices\VirgilCards\CardsServiceInterface;

use Virgil\Sdk\Client\VirgilServices\VirgilRegistrationAuthority\RegistrationAuthorityServiceInterface;

abstract class AbstractVirgilClientTest extends BaseTestCase
{
    /** @var VirgilClient */
    protected $virgilClient;

    /** @var CardsServiceInterface */
    protected $cardsServiceMock;

    /** @var RegistrationAuthorityServiceInterface */
    protected $registrationAuthorityServiceMock;

    /** @var IdentityServiceInterface */
    protected $identityService;


    public function setUp()
    {
        $virgilClientParams = $this->createVirgilClientParams();

        $this->cardsServiceMock = $this->createCardsService();

        $this->registrationAuthorityServiceMock = $this->createRegistrationAuthorityService();

        $this->identityService = $this->createIdentityService();

        $this->virgilClient = $this->getVirgilClient(
            $virgilClientParams,
            $this->cardsServiceMock,
            $this->registrationAuthorityServiceMock,
            $this->identityService
        );
    }


    protected abstract function configureVirgilServiceResponse($with, $response);


    protected function createCardsService()
    {
        return $this->createMock(CardsServiceInterface::class);
    }


    protected function createRegistrationAuthorityService()
    {
        return $this->createMock(RegistrationAuthorityServiceInterface::class);
    }


    protected function getVirgilClient(
        $virgilClientParams,
        $cardsServiceMock,
        $registrationAuthorityServiceMock,
        $identityServiceMock
    ) {
        return new VirgilClient(
            $virgilClientParams,
            $cardsServiceMock,
            $registrationAuthorityServiceMock,
            $identityServiceMock
        );
    }


    protected function createVirgilClientParams()
    {
        return new VirgilClientParams('asfja8');
    }


    protected function createIdentityService()
    {
        return $this->createMock(IdentityServiceInterface::class);
    }
}
