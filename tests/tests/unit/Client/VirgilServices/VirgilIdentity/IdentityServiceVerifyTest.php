<?php
namespace Virgil\Sdk\Tests\Unit\Client\VirgilServices\VirgilIdentity;


use Virgil\Sdk\Client\Http\Constants\RequestMethods;

use Virgil\Sdk\Tests\Unit\Client\VirgilServices\VirgilIdentity\Model\RequestModel;
use Virgil\Sdk\Tests\Unit\Client\VirgilServices\VirgilIdentity\Model\ResponseModel;

class IdentityServiceVerifyTest extends AbstractIdentityServiceTest
{
    /**
     * @dataProvider verifyDataProvider
     *
     * @param $verifyIdentityRequestData
     * @param $expectedActionId
     * @param $expectedCurlRequestOptions
     * @param $expectedHttpClientResponseArgs
     *
     * @test
     */
    public function verify__withVerifyRequest__returnsValidResponse(
        $verifyIdentityRequestData,
        $expectedActionId,
        $expectedCurlRequestOptions,
        $expectedHttpClientResponseArgs
    ) {
        $expectedResponse = ResponseModel::createVerifyResponseModel($expectedActionId);
        $verifyIdentityRequest = RequestModel::createVerifyRequestModel(...$verifyIdentityRequestData);

        $this->configureHttpCurlClientResponse($expectedCurlRequestOptions, $expectedHttpClientResponseArgs);


        $verifyIdentityResponse = $this->virgilService->verify($verifyIdentityRequest);


        $this->assertEquals($expectedResponse, $verifyIdentityResponse);
    }


    public function verifyDataProvider()
    {
        return [
            [
                ['email', 'user@virgilsecurity.com', ['parameter 1' => 'value-1', 'parameter_1' => 'value2']],
                "202b65f1-ee1c-4cc2-941a-9548c9cded1c",
                [
                    CURLOPT_URL           => 'http://identity.service.host/v1/verify',
                    CURLOPT_CUSTOMREQUEST => RequestMethods::HTTP_POST,
                    CURLOPT_POST          => true,
                    CURLOPT_POSTFIELDS    => '{"type":"email","value":"user@virgilsecurity.com","extra_fields":{"parameter 1":"value-1","parameter_1":"value2"}}',
                    CURLOPT_HTTPHEADER    => [],
                ],
                [
                    '200',
                    [],
                    '{"action_id":"202b65f1-ee1c-4cc2-941a-9548c9cded1c"}',
                ],
            ],
        ];
    }
}
