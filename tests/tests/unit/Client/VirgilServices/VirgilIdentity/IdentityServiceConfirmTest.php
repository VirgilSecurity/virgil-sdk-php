<?php
namespace Virgil\Sdk\Tests\Unit\Client\VirgilServices\VirgilIdentity;


use Virgil\Sdk\Client\Http\Constants\RequestMethods;
use Virgil\Sdk\Tests\Unit\Client\VirgilServices\VirgilIdentity\Model\RequestModel;
use Virgil\Sdk\Tests\Unit\Client\VirgilServices\VirgilIdentity\Model\ResponseModel;

class IdentityServiceConfirmTest extends AbstractIdentityServiceTest
{
    /**
     * @dataProvider confirmDataProvider
     *
     * @param $confirmRequestData
     * @param $confirmResponseData
     * @param $expectedCurlRequestOptions
     * @param $expectedHttpClientResponseArgs
     *
     * @test
     */
    public function confirm__withConfirmRequest__returnsValidResponse(
        $confirmRequestData,
        $confirmResponseData,
        $expectedCurlRequestOptions,
        $expectedHttpClientResponseArgs
    ) {
        $expectedResponse = ResponseModel::createConfirmResponseModel(...$confirmResponseData);
        $confirmIdentityRequest = RequestModel::createConfirmRequestModel(...$confirmRequestData);

        $this->configureHttpCurlClientResponse($expectedCurlRequestOptions, $expectedHttpClientResponseArgs);


        $confirmIdentityResponse = $this->virgilService->confirm($confirmIdentityRequest);


        $this->assertEquals($expectedResponse, $confirmIdentityResponse);
    }


    public function confirmDataProvider()
    {
        return [
            [
                ['4R6S3H', '202b65f1-ee1c-4cc2-941a-9548c9cded1c', ['3600', '12']],
                [
                    'email',
                    'user@virgilsecurity.com',
                    'MIGZMA0GCWCGSAFlAwQCAgUABIGHMIGEAkB0RVkqJ89UlvsbBDgA2nPNVEhRptbF8ZVFXrZGbzSmLU9OLw2A',
                ],

                [
                    CURLOPT_URL           => 'http://identity.service.host/v1/confirm',
                    CURLOPT_CUSTOMREQUEST => RequestMethods::HTTP_POST,
                    CURLOPT_POST          => true,
                    CURLOPT_POSTFIELDS    => '{"confirmation_code":"4R6S3H","action_id":"202b65f1-ee1c-4cc2-941a-9548c9cded1c","token":{"time_to_live":"3600","count_to_live":"12"}}',
                    CURLOPT_HTTPHEADER    => [],
                ],
                [
                    '200',
                    [],
                    '{"type":"email","value":"user@virgilsecurity.com","validation_token":"MIGZMA0GCWCGSAFlAwQCAgUABIGHMIGEAkB0RVkqJ89UlvsbBDgA2nPNVEhRptbF8ZVFXrZGbzSmLU9OLw2A"}',
                ],
            ],
        ];
    }
}
