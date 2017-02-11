<?php
namespace Virgil\Sdk\Tests\Unit\Client;


use DateTime;

use Virgil\Sdk\Buffer;

use Virgil\Sdk\Client\Requests\Constants\CardScopes;

use Virgil\Sdk\Client\VirgilCards\Model\DeviceInfoModel;

use Virgil\Sdk\Tests\Unit\Card as VirgilClientCard;

use Virgil\Sdk\Tests\Unit\Client\Requests\CardRequest;

use Virgil\Sdk\Tests\Unit\Client\VirgilCards\Model\RequestModel;
use Virgil\Sdk\Tests\Unit\Client\VirgilCards\Model\ResponseModel;

class VirgilClientSearchCardsTest extends AbstractVirgilClientTest
{
    /**
     * @dataProvider getSearchDataProvider
     *
     * @param array $searchCardRequestArgs
     * @param array $expectedCardsArgs
     * @param array $searchRequestArgs
     * @param array $responseModelsArgs
     *
     * @test
     */
    public function searchCards__withSearchCardRequest__returnsCards(
        array $searchCardRequestArgs,
        array $expectedCardsArgs,
        array $searchRequestArgs,
        array $responseModelsArgs
    ) {
        $this->configureCardsServiceResponse($searchRequestArgs, $responseModelsArgs);

        $searchCardRequest = CardRequest::createSearchCardRequest(...$searchCardRequestArgs);

        $expectedCards = VirgilClientCard::createCards($expectedCardsArgs);


        $cards = $this->virgilClient->searchCards($searchCardRequest);


        $this->assertEquals($expectedCards, $cards);
    }


    public function getSearchDataProvider()
    {
        return [
            [
                [
                    ['email', CardScopes::TYPE_GLOBAL],
                    ['user@virgilsecurity.com', 'another.user@virgilsecurity.com'],
                ],
                [
                    [
                        'model-id-1',
                        Buffer::fromBase64(
                            'eyJpZGVudGl0eSI6ImFsaWNlMiIsImlkZW50aXR5X3R5cGUiOiJtZW1iZXIiLCJwdWJsaWNfa2V5IjoicHVibGljLWtleS0yIiwic2NvcGUiOiJnbG9iYWwifQ=='
                        ),
                        'alice2',
                        'member',
                        Buffer::fromBase64('public-key-2'),
                        CardScopes::TYPE_GLOBAL,
                        [],
                        null,
                        null,
                        'v4',
                        ['sign-id-3' => Buffer::fromBase64('X3NpZ24z')],
                    ],
                    [
                        'model-id-2',
                        Buffer::fromBase64(
                            'eyJpZGVudGl0eSI6ImFsaWNlMyIsImlkZW50aXR5X3R5cGUiOiJtZW1iZXIiLCJwdWJsaWNfa2V5IjoicHVibGljLWtleS0yIiwiZGF0YSI6eyJjdXN0b21EYXRhIjoicXdlcnR5In0sInNjb3BlIjoiYXBwbGljYXRpb24iLCJpbmZvIjp7ImRldmljZSI6ImlQaG9uZTZzIiwiZGV2aWNlX25hbWUiOiJTcGFjZSBncmV5IG9uZSJ9fQ=='
                        ),
                        'alice3',
                        'member',
                        Buffer::fromBase64('public-key-2'),
                        CardScopes::TYPE_APPLICATION,
                        ['customData' => 'qwerty'],
                        'iPhone6s',
                        'Space grey one',
                        'v4',
                        ['sign-id-3' => Buffer::fromBase64('X3NpZ24z')],
                    ],
                ],
                [
                    ['user@virgilsecurity.com', 'another.user@virgilsecurity.com'],
                    'email',
                    CardScopes::TYPE_GLOBAL,
                ],
                [
                    [
                        'model-id-1',
                        'eyJpZGVudGl0eSI6ImFsaWNlMiIsImlkZW50aXR5X3R5cGUiOiJtZW1iZXIiLCJwdWJsaWNfa2V5IjoicHVibGljLWtleS0yIiwic2NvcGUiOiJnbG9iYWwifQ==',
                        ['alice2', 'member', 'public-key-2', CardScopes::TYPE_GLOBAL],
                        [['sign-id-3' => 'X3NpZ24z'], new DateTime('2016-11-04T13:16:17+0000'), 'v4'],
                    ],
                    [
                        'model-id-2',
                        'eyJpZGVudGl0eSI6ImFsaWNlMyIsImlkZW50aXR5X3R5cGUiOiJtZW1iZXIiLCJwdWJsaWNfa2V5IjoicHVibGljLWtleS0yIiwiZGF0YSI6eyJjdXN0b21EYXRhIjoicXdlcnR5In0sInNjb3BlIjoiYXBwbGljYXRpb24iLCJpbmZvIjp7ImRldmljZSI6ImlQaG9uZTZzIiwiZGV2aWNlX25hbWUiOiJTcGFjZSBncmV5IG9uZSJ9fQ==',
                        [
                            'alice3',
                            'member',
                            'public-key-2',
                            CardScopes::TYPE_APPLICATION,
                            ['customData' => 'qwerty'],
                            new DeviceInfoModel('iPhone6s', 'Space grey one'),
                        ],
                        [['sign-id-3' => 'X3NpZ24z'], new DateTime('2016-11-04T13:16:17+0000'), 'v4'],
                    ],
                ],
            ],
        ];
    }


    protected function configureCardsServiceResponse($searchRequestArgs, $responseModelsArgs)
    {
        $searchRequestModel = RequestModel::createSearchRequestModel(...$searchRequestArgs);

        $responseModels = ResponseModel::createSignedResponseModels($responseModelsArgs);

        $this->cardsServiceMock->expects($this->once())
                               ->method('search')
                               ->with($searchRequestModel)
                               ->willReturn($responseModels)
        ;
    }
}
