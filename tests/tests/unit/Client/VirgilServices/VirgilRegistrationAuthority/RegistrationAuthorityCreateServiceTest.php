<?php
namespace Virgil\Sdk\Tests\Unit\Client\VirgilServices\VirgilRegistrationAuthority;


use DateTime;

use Virgil\Sdk\Client\Http\Constants\RequestMethods;

use Virgil\Sdk\Client\Requests\Constants\CardScopes;

use Virgil\Sdk\Tests\Unit\Client\VirgilServices\Model\RequestModel;
use Virgil\Sdk\Tests\Unit\Client\VirgilServices\Model\ResponseModel;

use Virgil\Sdk\Tests\Unit\Client\VirgilServices\RegistrationAuthority\AbstractRegistrationAuthorityServiceTest;

class RegistrationAuthorityCreateServiceTest extends AbstractRegistrationAuthorityServiceTest
{
    /**
     * @dataProvider creteDataProvider
     *
     * @param $createRequestData
     * @param $createResponseData
     * @param $expectedCurlRequestOptions
     * @param $expectedHttpClientResponseArgs
     *
     * @test
     */
    public function create__withCreateRequest__returnsValidResponse(
        $createRequestData,
        $createResponseData,
        $expectedCurlRequestOptions,
        $expectedHttpClientResponseArgs
    ) {
        $expectedResponse = ResponseModel::createSignedResponseModel(...$createResponseData);
        $createCardRequest = RequestModel::createCreateCardRequestModel(...$createRequestData);

        $this->configureHttpCurlClientResponse($expectedCurlRequestOptions, $expectedHttpClientResponseArgs);


        $createResponse = $this->virgilService->create($createCardRequest);


        $this->assertEquals($expectedResponse, $createResponse);
    }


    public function creteDataProvider()
    {
        return [
            [
                [
                    ['alice', 'member', 'public-key', CardScopes::TYPE_GLOBAL],
                    [
                        ['sign-id-1' => '_sign1', 'sign-id-2' => '_sign2'],
                        'MIGZMA0GCWCGSAFlAwQCAgUABIGHMIGEAkB0RVkqJ89UlvsbBDgA2nPNVEhRptbF8ZVFXrZGbzSmLU9OLw2A',
                    ],
                ],
                [
                    'model-id-2',
                    'eyJpZGVudGl0eSI6ImFsaWNlMiIsImlkZW50aXR5X3R5cGUiOiJtZW1iZXIiLCJwdWJsaWNfa2V5IjoicHVibGljLWtleS0yIiwic2NvcGUiOiJnbG9iYWwifQ==',
                    ['alice2', 'member', 'public-key-2', CardScopes::TYPE_GLOBAL],
                    [
                        ['sign-id-3' => '_sign3', 'sign-id-4' => '_sign4'],
                        new DateTime('2016-11-04T13:16:17+0000'),
                        'v4',
                    ],
                ],
                [
                    CURLOPT_URL           => 'https://ra.virgilsecurity.com/v1/card',
                    CURLOPT_HTTPHEADER    => [],
                    CURLOPT_CUSTOMREQUEST => RequestMethods::HTTP_POST,
                    CURLOPT_POST          => true,
                    CURLOPT_POSTFIELDS    => '{"content_snapshot":"eyJpZGVudGl0eSI6ImFsaWNlIiwiaWRlbnRpdHlfdHlwZSI6Im1lbWJlciIsInB1YmxpY19rZXkiOiJwdWJsaWMta2V5Iiwic2NvcGUiOiJnbG9iYWwifQ==","meta":{"signs":{"sign-id-1":"_sign1","sign-id-2":"_sign2"},"validation":{"token":"MIGZMA0GCWCGSAFlAwQCAgUABIGHMIGEAkB0RVkqJ89UlvsbBDgA2nPNVEhRptbF8ZVFXrZGbzSmLU9OLw2A"}}}',
                ],
                [
                    '200',
                    [],
                    '{"id":"model-id-2","content_snapshot":"eyJpZGVudGl0eSI6ImFsaWNlMiIsImlkZW50aXR5X3R5cGUiOiJtZW1iZXIiLCJwdWJsaWNfa2V5IjoicHVibGljLWtleS0yIiwic2NvcGUiOiJnbG9iYWwifQ==","meta":{"created_at":"2016-11-04T13:16:17+0000","card_version":"v4","signs":{"sign-id-3":"_sign3","sign-id-4":"_sign4"}}}',
                ],
            ],
        ];
    }
}
