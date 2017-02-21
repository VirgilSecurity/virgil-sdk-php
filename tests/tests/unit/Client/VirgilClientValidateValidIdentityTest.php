<?php
namespace Virgil\Sdk\Tests\Unit\Client;


use Virgil\Sdk\Tests\Unit\Client\VirgilServices\VirgilIdentity\Model\RequestModel;

class VirgilClientValidateValidIdentityTest extends AbstractVirgilClientTest
{
    /**
     * @dataProvider validIdentityDataProvider
     *
     * @param array $validateRequestArgs
     *
     * @test
     */
    public function isIdentityValid__withValidIdentityParams__returnsTrue(
        array $validateRequestArgs
    ) {
        $this->configureVirgilServiceResponse($validateRequestArgs);

        list($identityType, $identity, $validationToken) = $validateRequestArgs;


        $isIdentityValid = $this->virgilClient->isIdentityValid($identityType, $identity, $validationToken);


        $this->assertEquals(true, $isIdentityValid);
    }


    public function validIdentityDataProvider()
    {
        return [
            [
                [
                    'email',
                    'user@virgilsecurity.com',
                    'MIGZMA0GCWCGSAFlAwQCAgUABIGHMIGEAkB0RVkqJ89UlvsbBDgA2nPNVEhRptbF8ZVFXrZGbzSmLU9OLw2A',
                ],
            ],
        ];
    }


    protected function configureVirgilServiceResponse($validateRequestArgs, $validateResponseArgs = null)
    {
        $validateRequest = RequestModel::createValidateRequestModel(...$validateRequestArgs);

        $this->identityService->expects($this->once())
                              ->method('validate')
                              ->with($validateRequest)
                              ->willReturn($this->identityService)
        ;;
    }
}
