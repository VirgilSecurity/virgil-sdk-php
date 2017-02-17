<?php
namespace Virgil\Sdk\Tests\Unit\Client;


use Virgil\Sdk\Tests\Unit\Client\VirgilServices\VirgilIdentity\Model\RequestModel;
use Virgil\Sdk\Tests\Unit\Client\VirgilServices\VirgilIdentity\Model\ResponseModel;

class VirgilClientVerifyIdentityTest extends AbstractVirgilClientTest
{
    /**
     * @dataProvider verifyIdentityDataProvider
     *
     * @param array  $verifyRequestArgs
     * @param string $expectedActionId
     *
     * @test
     */
    public function verifyIdentity__withVerifyIdentityParams__returnsActionId(
        array $verifyRequestArgs,
        $expectedActionId
    ) {
        $this->configureVirgilServiceResponse($verifyRequestArgs, $expectedActionId);

        list($identityType, $identity, $extraFields) = $verifyRequestArgs;

        $actionId = $this->virgilClient->verifyIdentity($identity, $identityType, $extraFields);


        $this->assertEquals($expectedActionId, $actionId);
    }


    public function verifyIdentityDataProvider()
    {
        return [
            [
                ['email', 'user@virgilsecurity.com', ['parameter 1' => 'value-1', 'parameter_1' => 'value2']],
                "202b65f1-ee1c-4cc2-941a-9548c9cded1c",
            ],
        ];
    }


    protected function configureVirgilServiceResponse($verifyRequestArgs, $actionId)
    {
        $verifyRequest = RequestModel::createVerifyRequestModel(...$verifyRequestArgs);

        $verifyResponse = ResponseModel::createVerifyResponseModel($actionId);

        $this->identityService->expects($this->once())
                              ->method('verify')
                              ->with($verifyRequest)
                              ->willReturn($verifyResponse)
        ;
    }
}
