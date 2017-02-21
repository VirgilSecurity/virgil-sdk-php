<?php
namespace Virgil\Sdk\Tests\Unit\Client\VirgilServices\VirgilCards;


use DateTime;

use Virgil\Sdk\Client\Http\Constants\RequestMethods;

use Virgil\Sdk\Client\Requests\Constants\CardScopes;

use Virgil\Sdk\Client\VirgilServices\Model\DeviceInfoModel;

use Virgil\Sdk\Tests\Unit\Client\VirgilServices\VirgilCards\Model\ResponseModel;

class CardsServiceGetCardTest extends AbstractCardsServiceTest
{
    /**
     * @dataProvider getCardDataProvider
     *
     * @param $expectedCurlRequestOptions
     * @param $expectedHttpClientResponseArgs
     * @param $expectedCardsServiceResponseArgs
     * @param $requestId
     *
     * @test
     */
    public function getCard__withRequestId__returnsValidResponse(
        $requestId,
        $expectedCardsServiceResponseArgs,
        $expectedCurlRequestOptions,
        $expectedHttpClientResponseArgs
    ) {
        $this->configureHttpCurlClientResponse($expectedCurlRequestOptions, $expectedHttpClientResponseArgs);

        $expectedCardsServiceResponse = ResponseModel::createSignedResponseModel(...$expectedCardsServiceResponseArgs);


        $cardsServiceResponse = $this->virgilService->get($requestId);


        $this->assertEquals($expectedCardsServiceResponse, $cardsServiceResponse);
    }


    /**
     * @return array
     */
    public function getCardDataProvider()
    {
        return [
            [
                'model-id-1',
                [
                    'model-id-1',
                    'eyJpZGVudGl0eSI6ImFsaWNlMiIsImlkZW50aXR5X3R5cGUiOiJtZW1iZXIiLCJwdWJsaWNfa2V5IjoicHVibGljLWtleS0yIiwiZGF0YSI6eyJjdXN0b21EYXRhIjoicXdlcnR5In0sInNjb3BlIjoiZ2xvYmFsIiwiaW5mbyI6eyJkZXZpY2UiOiJpUGhvbmU2cyIsImRldmljZV9uYW1lIjoiU3BhY2UgZ3JleSBvbmUifX0=',
                    [
                        'alice2',
                        'member',
                        'public-key-2',
                        CardScopes::TYPE_GLOBAL,
                        ['customData' => 'qwerty'],
                        new DeviceInfoModel('iPhone6s', 'Space grey one'),
                    ],
                    [
                        ['sign-id-3' => '_sign3', 'sign-id-4' => '_sign4'],
                        new DateTime('2016-11-04T13:16:17+0000'),
                        'v4',
                    ],
                ],
                [
                    CURLOPT_URL           => 'http://immutable.host/card/model-id-1',
                    CURLOPT_HTTPHEADER    => [
                        sprintf('Authorization: %s', self::VIRGIL_CARDS_ACCESS_TOKEN),
                    ],
                    CURLOPT_CUSTOMREQUEST => RequestMethods::HTTP_GET,
                    CURLOPT_HTTPGET       => true,
                ],
                [
                    '200',
                    [],
                    '{"id":"model-id-1","content_snapshot":"eyJpZGVudGl0eSI6ImFsaWNlMiIsImlkZW50aXR5X3R5cGUiOiJtZW1iZXIiLCJwdWJsaWNfa2V5IjoicHVibGljLWtleS0yIiwiZGF0YSI6eyJjdXN0b21EYXRhIjoicXdlcnR5In0sInNjb3BlIjoiZ2xvYmFsIiwiaW5mbyI6eyJkZXZpY2UiOiJpUGhvbmU2cyIsImRldmljZV9uYW1lIjoiU3BhY2UgZ3JleSBvbmUifX0=","meta":{"created_at":"2016-11-04T13:16:17+0000","card_version":"v4","signs":{"sign-id-3":"_sign3","sign-id-4":"_sign4"}}}',
                ],
            ],
        ];
    }


    public function withoutAuthorizationHeaderDataProvider()
    {
        return [
            [
                [
                    CURLOPT_URL           => 'http://immutable.host/card/card-id',
                    CURLOPT_HTTPHEADER    => [], //empty auth header
                    CURLOPT_CUSTOMREQUEST => RequestMethods::HTTP_GET,
                    CURLOPT_HTTPGET       => true,
                ],
                ['401', [], '{"code":"20300"}'],
            ],
        ];
    }
}
