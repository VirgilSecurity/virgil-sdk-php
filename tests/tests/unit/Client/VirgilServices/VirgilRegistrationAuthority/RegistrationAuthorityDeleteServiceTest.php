<?php
namespace Virgil\Sdk\Tests\Unit\Client\VirgilServices\VirgilRegistrationAuthority;


use Virgil\Sdk\Client\Http\Constants\RequestMethods;

use Virgil\Sdk\Tests\Unit\Client\VirgilServices\Model\RequestModel;

use Virgil\Sdk\Tests\Unit\Client\VirgilServices\RegistrationAuthority\AbstractRegistrationAuthorityServiceTest;

class RegistrationAuthorityDeleteServiceTest extends AbstractRegistrationAuthorityServiceTest
{
    /**
     * @dataProvider deleteDataProvider
     *
     * @param $deleteRequestData
     * @param $expectedCurlRequestOptions
     * @param $expectedHttpClientResponseArgs
     *
     * @test
     */
    public function delete__withDeleteRequest__returnsSelfOnSuccess(
        $deleteRequestData,
        $expectedCurlRequestOptions,
        $expectedHttpClientResponseArgs
    ) {
        $deleteCardRequest = RequestModel::createRevokeCardRequestModel(...$deleteRequestData);

        $this->configureHttpCurlClientResponse($expectedCurlRequestOptions, $expectedHttpClientResponseArgs);


        $virgilService = $this->virgilService->delete($deleteCardRequest);


        $this->assertEquals($this->virgilService, $virgilService);
    }


    public function deleteDataProvider()
    {
        return [
            [
                [
                    [
                        'alice-fingerprint-id-1',
                        'compromised',
                    ],
                    [
                        ['sign-id-1' => '_sign1', 'sign-id-2' => '_sign2'],
                        'MIGZMA0GCWCGSAFlAwQCAgUABIGHMIGEAkB0RVkqJ89UlvsbBDgA2nPNVEhRptbF8ZVFXrZGbzSmLU9OLw2A',
                    ],
                ],
                [
                    CURLOPT_URL           => 'https://ra.virgilsecurity.com/v1/card/alice-fingerprint-id-1',
                    CURLOPT_HTTPHEADER    => [],
                    CURLOPT_CUSTOMREQUEST => RequestMethods::HTTP_DELETE,
                    CURLOPT_POST          => true,
                    CURLOPT_POSTFIELDS    => '{"content_snapshot":"eyJjYXJkX2lkIjoiYWxpY2UtZmluZ2VycHJpbnQtaWQtMSIsInJldm9jYXRpb25fcmVhc29uIjoiY29tcHJvbWlzZWQifQ==","meta":{"signs":{"sign-id-1":"_sign1","sign-id-2":"_sign2"},"validation":{"token":"MIGZMA0GCWCGSAFlAwQCAgUABIGHMIGEAkB0RVkqJ89UlvsbBDgA2nPNVEhRptbF8ZVFXrZGbzSmLU9OLw2A"}}}',
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
