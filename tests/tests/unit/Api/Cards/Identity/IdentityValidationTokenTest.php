<?php
namespace Virgil\Sdk\Tests\Unit\Api\Cards\Identity;


use Virgil\Sdk\Tests\BaseTestCase;

use Virgil\Sdk\Client\Requests\Constants\IdentityTypes;

use Virgil\Sdk\Client\VirgilClientInterface;

use Virgil\Sdk\Tests\Unit\Api\Cards\Identity;

class IdentityValidationTokenTest extends BaseTestCase
{
    /**
     * @dataProvider isValidForIdentityValidationToken
     *
     * @param $token
     * @param $identity
     * @param $identityType
     *
     * @test
     */
    public function isValid__forIdentityValidationToken__callsVirgilClientIsIdentityValid(
        $token,
        $identity,
        $identityType
    ) {
        $virgilClient = $this->createVirgilClient();

        $identityValidationToken = Identity::createIdentityValidationToken(
            $virgilClient,
            $token,
            $identity,
            $identityType
        );


        $virgilClient->expects($this->once())
                     ->method('isIdentityValid')
                     ->with($identity, $identityType, $token)
        ;


        $identityValidationToken->isValid();
    }


    public function isValidForIdentityValidationToken()
    {
        return [
            [
                'MIGZMA0GCWCGSAFlAwQCAgUABIGHMIGEAkB0RVkqJ89UlvsbBDgA2nPNVEhRptbF8ZVFXrZGbzSmLU9OLw2A',
                'user@virgilsecurity.com',
                IdentityTypes::TYPE_EMAIL,
            ],
        ];
    }


    protected function createVirgilClient()
    {
        return $this->createMock(VirgilClientInterface::class);
    }

}
