<?php
namespace Virgil\Sdk\Tests\Unit\Client\VirgilServices\VirgilIdentity;


use Virgil\Sdk\Client\Http\Constants\RequestMethods;
use Virgil\Sdk\Tests\Unit\Client\VirgilServices\VirgilIdentity\Model\RequestModel;

class IdentityServiceValidateTest extends AbstractIdentityServiceTest
{
    /**
     * @dataProvider validateDataProvider
     *
     * @param $validateRequestData
     * @param $expectedCurlRequestOptions
     * @param $expectedHttpClientResponseArgs
     *
     * @test
     */
    public function validate__withValidateRequest__returnsSelfOnSuccess(
        $validateRequestData,
        $expectedCurlRequestOptions,
        $expectedHttpClientResponseArgs
    ) {
        $validateIdentityRequest = RequestModel::createValidateRequestModel(...$validateRequestData);

        $this->configureHttpCurlClientResponse($expectedCurlRequestOptions, $expectedHttpClientResponseArgs);


        $virgilIdentityService = $this->virgilService->validate($validateIdentityRequest);


        $this->assertEquals($this->virgilService, $virgilIdentityService);
    }


    public function validateDataProvider()
    {
        return [
            [
                [
                    'email',
                    'user@virgilsecurity.com',
                    'MIGZMA0GCWCGSAFlAwQCAgUABIGHMIGEAkB0RVkqJ89UlvsbBDgA2nPNVEhRptbF8ZVFXrZGbzSmLU9OLw2A',
                ],
                [
                    CURLOPT_URL           => 'http://identity.service.host/v1/validate',
                    CURLOPT_CUSTOMREQUEST => RequestMethods::HTTP_POST,
                    CURLOPT_POST          => true,
                    CURLOPT_POSTFIELDS    => '{"type":"email","value":"user@virgilsecurity.com","validation_token":"MIGZMA0GCWCGSAFlAwQCAgUABIGHMIGEAkB0RVkqJ89UlvsbBDgA2nPNVEhRptbF8ZVFXrZGbzSmLU9OLw2A"}',
                    CURLOPT_HTTPHEADER    => [],
                ],
                [
                    '200',
                    [],
                    '{}',
                ],
            ],
        ];
    }
}
