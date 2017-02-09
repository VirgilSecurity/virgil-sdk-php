<?php
namespace Virgil\Sdk\Tests\Unit\Client\VirgilIdentity;


use Virgil\Sdk\Client\VirgilIdentity\IdentityService;
use Virgil\Sdk\Client\VirgilIdentity\IdentityServiceParams;

use Virgil\Sdk\Tests\Unit\Client\AbstractVirgilServiceTest;
use Virgil\Sdk\Tests\Unit\Client\VirgilIdentity\Mapper\MappersCollection;

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
