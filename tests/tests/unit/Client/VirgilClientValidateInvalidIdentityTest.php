<?php
namespace Virgil\Sdk\Tests\Unit\Client;


use Virgil\Sdk\Client\VirgilServices\UnsuccessfulResponseException;

use Virgil\Sdk\Tests\Unit\Client\VirgilServices\VirgilIdentity\Model\RequestModel;

class VirgilClientValidateInvalidIdentityTest extends AbstractVirgilClientTest
{
    /**
     * @dataProvider notValidIdentityDataProvider
     *
     * @param array  $validateRequestArgs
     * @param string $errorResponseArgs
     *
     * @test
     */
    public function isIdentityValid__withNotValidIdentityParams__returnsFalse(
        array $validateRequestArgs,
        $errorResponseArgs
    ) {
        $this->configureVirgilServiceResponse($validateRequestArgs, $errorResponseArgs);

        list($identityType, $identity, $validationToken) = $validateRequestArgs;


        $isIdentityValid = $this->virgilClient->isIdentityValid($identityType, $identity, $validationToken);


        $this->assertEquals(false, $isIdentityValid);
    }


    public function notValidIdentityDataProvider()
    {
        return [
            [
                [
                    'email',
                    'user@virgilsecurity.com',
                    'MIGZMA0GCWCGSAFlAwQCAgUABIGHMIGEAkB0RVkqJ89UlvsbBDgA2nPNVEhRptbF8ZVFXrZGbzSmLU9OLw2A',
                ],
                [
                    '40200',
                    'Email identity value validation failed',
                ],
            ],
        ];
    }


    protected function configureVirgilServiceResponse($validateRequestArgs, $errorResponseArgs)
    {
        list($errorCode, $errorMessage) = $errorResponseArgs;

        $validateRequest = RequestModel::createValidateRequestModel(...$validateRequestArgs);

        $unsuccessfulResponseException = new UnsuccessfulResponseException($errorMessage, '400', $errorCode);

        $this->identityService->expects($this->once())
                              ->method('validate')
                              ->with($validateRequest)
                              ->willThrowException($unsuccessfulResponseException)
        ;
    }
}
