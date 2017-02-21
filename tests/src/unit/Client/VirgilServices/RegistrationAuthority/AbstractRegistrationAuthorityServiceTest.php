<?php
namespace Virgil\Sdk\Tests\Unit\Client\VirgilServices\RegistrationAuthority;


use Virgil\Sdk\Client\Http\HttpClientInterface;

use Virgil\Sdk\Client\VirgilServices\Mapper\AbstractErrorResponseModelMapper;

use Virgil\Sdk\Tests\Unit\Client\AbstractVirgilServiceTest;

use Virgil\Sdk\Client\VirgilServices\VirgilRegistrationAuthority\RegistrationAuthorityService;
use Virgil\Sdk\Client\VirgilServices\VirgilRegistrationAuthority\RegistrationAuthorityServiceInterface;
use Virgil\Sdk\Client\VirgilServices\VirgilRegistrationAuthority\RegistrationAuthorityServiceParams;

use Virgil\Sdk\Tests\Unit\Client\VirgilServices\RegistrationAuthority\Mapper\MappersCollection;

class AbstractRegistrationAuthorityServiceTest extends AbstractVirgilServiceTest
{
    /** @var RegistrationAuthorityServiceInterface */
    protected $virgilService;


    /**
     * @inheritdoc
     */
    function createErrorResponseModelMapper()
    {
        return $this->getMockForAbstractClass(AbstractErrorResponseModelMapper::class);
    }


    /**
     * @inheritdoc
     *
     * @return RegistrationAuthorityServiceInterface
     */
    protected function getService(HttpClientInterface $httpClient)
    {
        $params = new RegistrationAuthorityServiceParams('https://ra.virgilsecurity.com');

        return new RegistrationAuthorityService($params, $httpClient, MappersCollection::getMappers());
    }
}
