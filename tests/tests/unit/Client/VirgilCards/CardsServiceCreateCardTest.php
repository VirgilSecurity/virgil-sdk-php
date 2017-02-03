<?php
namespace Virgil\Sdk\Tests\Unit\Client\VirgilCards;


use DateTime;

use Virgil\Sdk\Client\Http\Constants\RequestMethods;

use Virgil\Sdk\Client\Requests\Constants\CardScopes;

use Virgil\Sdk\Tests\Unit\Client\VirgilCards\Model\RequestModel;
use Virgil\Sdk\Tests\Unit\Client\VirgilCards\Model\ResponseModel;

class CardsServiceCreateCardTest extends AbstractCardsServiceTest
{
    /**
     * @dataProvider createCardDataProvider
     *
     * @param $cardServiceRequestModelArgs
     * @param $expectedCardsServiceResponseArgs
     * @param $expectedCurlRequestOptions
     * @param $expectedHttpClientResponseArgs
     *
     * @test
     */
    public function createCard__withCreateRequestModel__returnsValidResponse(
        $cardServiceRequestModelArgs,
        $expectedCardsServiceResponseArgs,
        $expectedCurlRequestOptions,
        $expectedHttpClientResponseArgs
    ) {
        $this->configureHttpCurlClientResponse($expectedCurlRequestOptions, $expectedHttpClientResponseArgs);

        $expectedCardsServiceResponse = ResponseModel::createSignedResponseModel(...$expectedCardsServiceResponseArgs);

        $cardServiceRequestModel = RequestModel::createCreateCardRequestModel(...$cardServiceRequestModelArgs);


        $cardsServiceResponse = $this->virgilService->create($cardServiceRequestModel);


        $this->assertEquals($expectedCardsServiceResponse, $cardsServiceResponse);
    }


    public function createCardDataProvider()
    {
        return [
            [
                [
                    ['alice', 'member', 'public-key', CardScopes::TYPE_APPLICATION],
                    ['sign-id-1' => '_sign1', 'sign-id-2' => '_sign2'],
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
                    CURLOPT_URL           => 'http://mutable.host/card',
                    CURLOPT_HTTPHEADER    => [
                        sprintf('Authorization: %s', self::VIRGIL_CARDS_ACCESS_TOKEN),
                    ],
                    CURLOPT_CUSTOMREQUEST => RequestMethods::HTTP_POST,
                    CURLOPT_POST          => true,
                    CURLOPT_POSTFIELDS    => '{"content_snapshot":"eyJpZGVudGl0eSI6ImFsaWNlIiwiaWRlbnRpdHlfdHlwZSI6Im1lbWJlciIsInB1YmxpY19rZXkiOiJwdWJsaWMta2V5Iiwic2NvcGUiOiJhcHBsaWNhdGlvbiJ9","meta":{"signs":{"sign-id-1":"_sign1","sign-id-2":"_sign2"}}}',
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
