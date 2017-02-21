<?php
namespace Virgil\Sdk\Tests\Unit\Client;


use Virgil\Sdk\Tests\Unit\Client\VirgilServices\VirgilIdentity\Model\RequestModel;
use Virgil\Sdk\Tests\Unit\Client\VirgilServices\VirgilIdentity\Model\ResponseModel;

class VirgilClientConfirmIdentityTest extends AbstractVirgilClientTest
{
    /**
     * @dataProvider confirmIdentityDataProvider
     *
     * @param array $confirmRequestArgs
     * @param array $confirmResponseArgs
     *
     * @test
     */
    public function confirmIdentity__withConfirmIdentityParams__returnsValidationId(
        array $confirmRequestArgs,
        array $confirmResponseArgs
    ) {
        $this->configureVirgilServiceResponse($confirmRequestArgs, $confirmResponseArgs);

        list($confirmationCode, $actionId, $tokenArgs) = $confirmRequestArgs;
        list(, , $expectedValidationToken) = $confirmResponseArgs;


        $validationToken = $this->virgilClient->confirmIdentity(
            $actionId,
            $confirmationCode,
            ...$tokenArgs
        );


        $this->assertEquals($expectedValidationToken, $validationToken);
    }


    public function confirmIdentityDataProvider()
    {
        return [
            [
                ['ffagf3fff3f3', '202b65f1-ee1c-4cc2-941a-9548c9cded1c', [3600, 1]],
                [
                    'email',
                    'user@virgilsecurity.com',
                    'MIGZMA0GCWCGSAFlAwQCAgUABIGHMIGEAkB0RVkqJ89UlvsbBDgA2nPNVEhRptbF8ZVFXrZGbzSmLU9OLw2A',
                ],
            ],
        ];
    }


    protected function configureVirgilServiceResponse($confirmRequestArgs, $confirmResponseArgs)
    {
        $confirmRequest = RequestModel::createConfirmRequestModel(...$confirmRequestArgs);

        $confirmResponse = ResponseModel::createConfirmResponseModel(...$confirmResponseArgs);

        $this->identityService->expects($this->once())
                              ->method('confirm')
                              ->with($confirmRequest)
                              ->willReturn($confirmResponse)
        ;
    }
}
