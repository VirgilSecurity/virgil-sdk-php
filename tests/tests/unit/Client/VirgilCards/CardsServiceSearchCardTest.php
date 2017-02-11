<?php
namespace Virgil\Sdk\Tests\Unit\Client\VirgilCards;


use DateTime;

use Virgil\Sdk\Client\Http\Constants\RequestMethods;

use Virgil\Sdk\Client\Requests\Constants\CardScopes;

use Virgil\Sdk\Client\VirgilCards\Model\DeviceInfoModel;

use Virgil\Sdk\Tests\Unit\Client\VirgilCards\Model\RequestModel;
use Virgil\Sdk\Tests\Unit\Client\VirgilCards\Model\ResponseModel;

class CardsServiceSearchCardTest extends AbstractCardsServiceTest
{

    /**
     * @dataProvider searchCardsDataProvider
     *
     * @param $cardServiceSearchRequestArgs
     * @param $expectedCardsServiceResponseArgs
     * @param $expectedCurlRequestOptions
     * @param $expectedHttpClientResponseArgs
     *
     * @test
     */
    public function searchCards__withSearchRequestModel__returnsValidResponse(
        $cardServiceSearchRequestArgs,
        $expectedCardsServiceResponseArgs,
        $expectedCurlRequestOptions,
        $expectedHttpClientResponseArgs
    ) {
        $this->configureHttpCurlClientResponse($expectedCurlRequestOptions, $expectedHttpClientResponseArgs);

        $searchRequestModel = RequestModel::createSearchRequestModel(...$cardServiceSearchRequestArgs);

        $expectedCardsServiceResponse = ResponseModel::createSignedResponseModels($expectedCardsServiceResponseArgs);


        $cardsServiceResponse = $this->virgilService->search($searchRequestModel);


        $this->assertEquals($expectedCardsServiceResponse, $cardsServiceResponse);
    }


    public function searchCardsDataProvider()
    {
        return [
            [
                [
                    ['user@virgilsecurity.com', 'another.user@virgilsecurity.com'],
                    'email',
                    CardScopes::TYPE_GLOBAL,
                ],
                [
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
                        'model-id-2',
                        'eyJpZGVudGl0eSI6ImFsaWNlMiIsImlkZW50aXR5X3R5cGUiOiJtZW1iZXIiLCJwdWJsaWNfa2V5IjoicHVibGljLWtleS0yIiwic2NvcGUiOiJnbG9iYWwifQ==',
                        ['alice2', 'member', 'public-key-2', CardScopes::TYPE_GLOBAL],
                        [
                            ['sign-id-3' => '_sign3', 'sign-id-4' => '_sign4'],
                            new DateTime('2016-11-04T13:16:17+0000'),
                            'v4',
                        ],
                    ],
                ],
                [
                    CURLOPT_URL           => 'http://immutable.host/card/actions/search',
                    CURLOPT_HTTPHEADER    => [
                        sprintf('Authorization: %s', self::VIRGIL_CARDS_ACCESS_TOKEN),
                    ],
                    CURLOPT_CUSTOMREQUEST => RequestMethods::HTTP_POST,
                    CURLOPT_POST          => true,
                    CURLOPT_POSTFIELDS    => '{"identities":["user@virgilsecurity.com","another.user@virgilsecurity.com"],"identity_type":"email","scope":"global"}',
                ],
                [
                    '200',
                    [],
                    '[{"id":"model-id-1","content_snapshot":"eyJpZGVudGl0eSI6ImFsaWNlMiIsImlkZW50aXR5X3R5cGUiOiJtZW1iZXIiLCJwdWJsaWNfa2V5IjoicHVibGljLWtleS0yIiwiZGF0YSI6eyJjdXN0b21EYXRhIjoicXdlcnR5In0sInNjb3BlIjoiZ2xvYmFsIiwiaW5mbyI6eyJkZXZpY2UiOiJpUGhvbmU2cyIsImRldmljZV9uYW1lIjoiU3BhY2UgZ3JleSBvbmUifX0=","meta":{"created_at":"2016-11-04T13:16:17+0000","card_version":"v4","signs":{"sign-id-3":"_sign3","sign-id-4":"_sign4"}}},{"id":"model-id-2","content_snapshot":"eyJpZGVudGl0eSI6ImFsaWNlMiIsImlkZW50aXR5X3R5cGUiOiJtZW1iZXIiLCJwdWJsaWNfa2V5IjoicHVibGljLWtleS0yIiwic2NvcGUiOiJnbG9iYWwifQ==","meta":{"created_at":"2016-11-04T13:16:17+0000","card_version":"v4","signs":{"sign-id-3":"_sign3","sign-id-4":"_sign4"}}}]',
                ],
            ],
        ];
    }
}
