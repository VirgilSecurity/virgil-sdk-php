<?php
namespace Virgil\Sdk\Tests\Unit\Client\VirgilServices\VirgilIdentity;


use Virgil\Sdk\Client\VirgilServices\VirgilIdentity\IdentityService;
use Virgil\Sdk\Client\VirgilServices\VirgilIdentity\IdentityServiceParams;

use Virgil\Sdk\Tests\Unit\Client\AbstractVirgilServiceTest;
use Virgil\Sdk\Tests\Unit\Client\VirgilServices\VirgilIdentity\Mapper\MappersCollection;

abstract class AbstractIdentityServiceTest extends AbstractVirgilServiceTest
{
    /** @var IdentityService */
    protected $virgilService;


    /**
     * @return IdentityService
     */
    protected function getService()
    {
        $identityServiceParams = new IdentityServiceParams('http://identity.service.host');

        return new IdentityService($identityServiceParams, $this->httpCurlClientMock, MappersCollection::getMappers());
    }
}
