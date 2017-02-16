<?php
namespace Virgil\Sdk\Tests\Unit\Client;


use DateTime;

use Virgil\Sdk\Buffer;

use Virgil\Sdk\Client\VirgilServices\Model\DeviceInfoModel;

use Virgil\Sdk\Client\Requests\Constants\CardScopes;

use Virgil\Sdk\Tests\Unit\Client\VirgilServices\VirgilCards\Model\ResponseModel;

use Virgil\Sdk\Tests\Unit\Card as VirgilClientCard;

class VirgilClientGetCardTest extends AbstractVirgilClientTest
{
    /**
     * @dataProvider getCardDataProvider
     *
     * @param $cardId
     * @param $cardsServiceResponseArgs
     * @param $expectedCardArgs
     *
     * @test
     */
    public function getCard__withCardId__returnsValidCard(
        $cardId,
        $expectedCardArgs,
        $cardsServiceResponseArgs
    ) {
        $this->configureCardsServiceResponse($cardId, $cardsServiceResponseArgs);

        $expectedCard = VirgilClientCard::createCard($expectedCardArgs);


        $card = $this->virgilClient->getCard($cardId);


        $this->assertEquals($expectedCard, $card);
    }


    public function getCardDataProvider()
    {
        return [
            [
                'model-id-1',
                [
                    'model-id-1',
                    Buffer::fromBase64(
                        'eyJpZGVudGl0eSI6ImFsaWNlMiIsImlkZW50aXR5X3R5cGUiOiJtZW1iZXIiLCJwdWJsaWNfa2V5IjoicHVibGljLWtleS0yIiwiZGF0YSI6eyJjdXN0b21EYXRhIjoicXdlcnR5In0sInNjb3BlIjoiZ2xvYmFsIiwiaW5mbyI6eyJkZXZpY2UiOiJpUGhvbmU2cyIsImRldmljZV9uYW1lIjoiU3BhY2UgZ3JleSBvbmUifX0='
                    ),
                    'alice2',
                    'member',
                    Buffer::fromBase64('public-key-2'),
                    CardScopes::TYPE_GLOBAL,
                    ['customData' => 'qwerty'],
                    'iPhone6s',
                    'Space grey one',
                    'v4',
                    ['sign-id-3' => Buffer::fromBase64('X3NpZ24z'), 'sign-id-4' => Buffer::fromBase64('X3NpZ240')],
                ],
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
                        ['sign-id-3' => 'X3NpZ24z', 'sign-id-4' => 'X3NpZ240'],
                        new DateTime('2016-11-04T13:16:17+0000'),
                        'v4',
                    ],

                ],
            ],
            [
                'model-id-2',
                [
                    'model-id-2',
                    Buffer::fromBase64(
                        'eyJpZGVudGl0eSI6ImFsaWNlMiIsImlkZW50aXR5X3R5cGUiOiJtZW1iZXIiLCJwdWJsaWNfa2V5IjoicHVibGljLWtleS0yIiwic2NvcGUiOiJhcHBsaWNhdGlvbiJ9'
                    ),
                    'alice2',
                    'member',
                    Buffer::fromBase64('public-key-2'),
                    CardScopes::TYPE_APPLICATION,
                    [],
                    null,
                    null,
                    'v4',
                    ['sign-id-3' => Buffer::fromBase64('X3NpZ24z'), 'sign-id-4' => Buffer::fromBase64('X3NpZ240')],
                ],
                [
                    'model-id-2',
                    'eyJpZGVudGl0eSI6ImFsaWNlMiIsImlkZW50aXR5X3R5cGUiOiJtZW1iZXIiLCJwdWJsaWNfa2V5IjoicHVibGljLWtleS0yIiwic2NvcGUiOiJhcHBsaWNhdGlvbiJ9',
                    [
                        'alice2',
                        'member',
                        'public-key-2',
                        CardScopes::TYPE_APPLICATION,
                    ],
                    [
                        ['sign-id-3' => 'X3NpZ24z', 'sign-id-4' => 'X3NpZ240'],
                        new DateTime('2016-11-04T13:16:17+0000'),
                        'v4',
                    ],
                ],
            ],
        ];
    }


    protected function configureCardsServiceResponse($cardId, $signedResponseModelArgs)
    {
        $cardsServiceResponse = ResponseModel::createSignedResponseModel(...$signedResponseModelArgs);

        $this->cardsServiceMock->expects($this->once())
                               ->method('get')
                               ->with($cardId)
                               ->willReturn($cardsServiceResponse)
        ;
    }
}
