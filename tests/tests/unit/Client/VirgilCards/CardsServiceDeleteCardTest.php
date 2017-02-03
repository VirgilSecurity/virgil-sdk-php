<?php
namespace Virgil\Sdk\Tests\Unit\Client\VirgilCards;


use Virgil\Sdk\Client\Http\Constants\RequestMethods;
use Virgil\Sdk\Tests\Unit\Client\VirgilCards\Model\RequestModel;

class CardsServiceDeleteCardTest extends AbstractCardsServiceTest
{

    /**
     * @dataProvider deleteDataProvider
     *
     * @param $cardServiceRequestModelArgs
     * @param $expectedCurlRequestOptions
     * @param $expectedHttpClientResponseArgs
     *
     * @test
     */
    public function deleteCard__withRevokeRequestModel__returnsSelfOnSuccess(
        $cardServiceRequestModelArgs,
        $expectedCurlRequestOptions,
        $expectedHttpClientResponseArgs
    ) {
        $this->configureHttpCurlClientResponse($expectedCurlRequestOptions, $expectedHttpClientResponseArgs);

        $revokeCardRequestModel = RequestModel::createRevokeCardRequestModel(...$cardServiceRequestModelArgs);


        $cardsService = $this->virgilService->delete($revokeCardRequestModel);


        $this->assertEquals($this->virgilService, $cardsService);
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
                    ['sign-id-1' => '_sign1', 'sign-id-2' => '_sign2'],
                ],
                [
                    CURLOPT_URL           => 'http://mutable.host/card/alice-fingerprint-id-1',
                    CURLOPT_HTTPHEADER    => [
                        sprintf('Authorization: %s', self::VIRGIL_CARDS_ACCESS_TOKEN),
                    ],
                    CURLOPT_CUSTOMREQUEST => RequestMethods::HTTP_DELETE,
                    CURLOPT_POST          => true,
                    CURLOPT_POSTFIELDS    => '{"content_snapshot":"eyJjYXJkX2lkIjoiYWxpY2UtZmluZ2VycHJpbnQtaWQtMSIsInJldm9jYXRpb25fcmVhc29uIjoiY29tcHJvbWlzZWQifQ==","meta":{"signs":{"sign-id-1":"_sign1","sign-id-2":"_sign2"}}}',
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
