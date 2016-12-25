<?php
namespace Virgil\Sdk\Tests\Unit\Client;


use DateTime;
use PHPUnit\Framework\TestCase;

use Virgil\Sdk\Buffer;
use Virgil\Sdk\Client\Card;
use Virgil\Sdk\Client\Requests\SearchCardRequest;
use Virgil\Sdk\Client\VirgilCards\CardsService;
use Virgil\Sdk\Client\VirgilCards\Model\CardContentModel;
use Virgil\Sdk\Client\VirgilCards\Model\DeviceInfoModel;
use Virgil\Sdk\Client\VirgilCards\Model\RevokeCardContentModel;
use Virgil\Sdk\Client\VirgilCards\Model\SignedRequestMetaModel;
use Virgil\Sdk\Client\VirgilCards\Model\SignedRequestModel;
use Virgil\Sdk\Client\VirgilCards\Model\SignedResponseMetaModel;
use Virgil\Sdk\Client\VirgilCards\Model\SignedResponseModel;
use Virgil\Sdk\Client\Requests\Constants\CardScopes;
use Virgil\Sdk\Client\Requests\Constants\RevocationReasons;
use Virgil\Sdk\Client\Requests\CreateCardRequest;
use Virgil\Sdk\Client\Requests\RevokeCardRequest;
use Virgil\Sdk\Client\VirgilClient;
use Virgil\Sdk\Client\VirgilClientParams;

class VirgilClientTest extends TestCase
{
    /**
     * @dataProvider getCardDataProvider
     *
     * @param $cardsServiceResponse
     * @param $expectedCard
     */
    public function testGetCard(SignedResponseModel $cardsServiceResponse, Card $expectedCard)
    {
        $cardsServiceMock = $this->createPartialMock(CardsService::class, ['get']);
        $cardsServiceMock->expects($this->once())
                         ->method('get')
                         ->with($cardsServiceResponse->getId())
                         ->willReturn($cardsServiceResponse)
        ;

        $virgilClientParams = new VirgilClientParams('asfja8');
        $client = new VirgilClient($virgilClientParams, $cardsServiceMock);
        $card = $client->getCard($cardsServiceResponse->getId());

        $this->assertEquals($expectedCard, $card);
    }


    /**
     * @dataProvider getRevokeDataProvider
     *
     * @param SignedRequestModel $requestModel
     * @param RevokeCardRequest  $revokeCardRequest
     */
    public function testRevokeCard(SignedRequestModel $requestModel, RevokeCardRequest $revokeCardRequest)
    {
        /** @var SignedRequestMetaModel $meta */
        $meta = $requestModel->getRequestMeta();
        foreach ($meta->getSigns() as $signKey => $sign) {
            $revokeCardRequest->appendSignature($signKey, Buffer::fromBase64($sign));
        }

        $cardsServiceMock = $this->createPartialMock(CardsService::class, ['delete']);
        $cardsServiceMock->expects($this->once())
                         ->method('delete')
                         ->with($requestModel)
                         ->willReturn([])
        ;

        $virgilClientParams = new VirgilClientParams('asfja8');
        $client = new VirgilClient($virgilClientParams, $cardsServiceMock);
        $client->revokeCard($revokeCardRequest);
    }


    /**
     * @dataProvider getCreateDataProvider
     *
     * @param SignedRequestModel $requestModel
     * @param SignedResponseModel $responseModel
     * @param CreateCardRequest $createCardRequest
     * @param Card $expectedCard
     */
    public function testCreateCard(
        SignedRequestModel $requestModel,
        SignedResponseModel $responseModel,
        CreateCardRequest $createCardRequest,
        Card $expectedCard
    ) {
        /** @var SignedRequestMetaModel $meta */
        $meta = $requestModel->getRequestMeta();
        foreach ($meta->getSigns() as $signKey => $sign) {
            $createCardRequest->appendSignature($signKey, Buffer::fromBase64($sign));
        }

        $cardsServiceMock = $this->createPartialMock(CardsService::class, ['create']);
        $cardsServiceMock->expects($this->once())
                         ->method('create')
                         ->with($requestModel)
                         ->willReturn($responseModel)
        ;

        $virgilClientParams = new VirgilClientParams('asfja8');
        $client = new VirgilClient($virgilClientParams, $cardsServiceMock);
        $card = $client->createCard($createCardRequest);
        $this->assertEquals($expectedCard, $card);
    }


    /**
     * @dataProvider getSearchDataProvider
     *
     * @param SearchCardRequest $searchCardRequest
     * @param array             $identities
     * @param array             $responseModels
     * @param array             $expectedCards
     */
    public function testSearchCard(
        SearchCardRequest $searchCardRequest,
        array $identities,
        array $responseModels,
        array $expectedCards
    ) {
        array_map(function ($identity) use ($searchCardRequest) {
            $searchCardRequest->appendIdentity($identity);
        }, $identities);

        $searchCriteria = $searchCardRequest->getSearchCriteria();

        $cardsServiceMock = $this->createPartialMock(CardsService::class, ['search']);
        $cardsServiceMock->expects($this->once())
                         ->method('search')
                         ->with($searchCriteria)
                         ->willReturn($responseModels)
        ;

        $virgilClientParams = new VirgilClientParams('asfja8');
        $client = new VirgilClient($virgilClientParams, $cardsServiceMock);
        $cards = $client->searchCards($searchCardRequest);
        $this->assertEquals($expectedCards, $cards);
    }


    public function getRevokeDataProvider()
    {
        return [
            [
                new SignedRequestModel(
                    new RevokeCardContentModel('model-id-1', RevocationReasons::TYPE_UNSPECIFIED),
                    new SignedRequestMetaModel(['sign-id-3' => 'X3NpZ24z', 'sign-id-4' => 'X3NpZ240'])
                ),
                new RevokeCardRequest('model-id-1', RevocationReasons::TYPE_UNSPECIFIED),
            ],
            [
                new SignedRequestModel(
                    new RevokeCardContentModel('model-id-2', RevocationReasons::TYPE_COMPROMISED),
                    new SignedRequestMetaModel(['sign-id-4' => 'X3NpZ240'])
                ),
                new RevokeCardRequest('model-id-2', RevocationReasons::TYPE_COMPROMISED),
            ],
        ];
    }


    public function getCardDataProvider()
    {
        return [
            [
                new SignedResponseModel(
                    'model-id-1',
                    'eyJpZGVudGl0eSI6ImFsaWNlMiIsImlkZW50aXR5X3R5cGUiOiJtZW1iZXIiLCJwdWJsaWNfa2V5IjoicHVibGljLWtleS0yIiwiZGF0YSI6eyJjdXN0b21EYXRhIjoicXdlcnR5In0sInNjb3BlIjoiZ2xvYmFsIiwiaW5mbyI6eyJkZXZpY2UiOiJpUGhvbmU2cyIsImRldmljZV9uYW1lIjoiU3BhY2UgZ3JleSBvbmUifX0=',
                    new CardContentModel(
                        'alice2',
                        'member',
                        'public-key-2',
                        CardScopes::TYPE_GLOBAL,
                        ['customData' => 'qwerty'],
                        new DeviceInfoModel('iPhone6s', 'Space grey one')
                    ),
                    new SignedResponseMetaModel(
                        ['sign-id-3' => 'X3NpZ24z', 'sign-id-4' => 'X3NpZ240'],
                        new DateTime('2016-11-04T13:16:17+0000'),
                        'v4'
                    )
                ),
                new Card(
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
                    ['sign-id-3' => Buffer::fromBase64('X3NpZ24z'), 'sign-id-4' => Buffer::fromBase64('X3NpZ240')]
                ),
            ],
            [
                new SignedResponseModel(
                    'model-id-2',
                    'eyJpZGVudGl0eSI6ImFsaWNlMiIsImlkZW50aXR5X3R5cGUiOiJtZW1iZXIiLCJwdWJsaWNfa2V5IjoicHVibGljLWtleS0yIiwic2NvcGUiOiJhcHBsaWNhdGlvbiJ9',
                    new CardContentModel('alice2', 'member', 'public-key-2', CardScopes::TYPE_APPLICATION),
                    new SignedResponseMetaModel(
                        ['sign-id-3' => 'X3NpZ24z', 'sign-id-4' => 'X3NpZ240'],
                        new DateTime('2016-11-04T13:16:17+0000'),
                        'v4'
                    )
                ),
                new Card(
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
                    ['sign-id-3' => Buffer::fromBase64('X3NpZ24z'), 'sign-id-4' => Buffer::fromBase64('X3NpZ240')]
                ),
            ],
        ];
    }


    public function getCreateDataProvider()
    {
        return [
            [
                new SignedRequestModel(
                    new CardContentModel(
                        'alice2',
                        'member',
                        base64_encode('public-key-2'),
                        CardScopes::TYPE_GLOBAL,
                        ['customData' => 'qwerty'],
                        new DeviceInfoModel('iPhone6s', 'Space grey one')
                    ), new SignedRequestMetaModel(['sign-id-3' => 'X3NpZ24z'])
                ),
                new SignedResponseModel(
                    'model-id-1',
                    'eyJpZGVudGl0eSI6ImFsaWNlMiIsImlkZW50aXR5X3R5cGUiOiJtZW1iZXIiLCJwdWJsaWNfa2V5IjoicHVibGljLWtleS0yIiwiZGF0YSI6eyJjdXN0b21EYXRhIjoicXdlcnR5In0sInNjb3BlIjoiZ2xvYmFsIiwiaW5mbyI6eyJkZXZpY2UiOiJpUGhvbmU2cyIsImRldmljZV9uYW1lIjoiU3BhY2UgZ3JleSBvbmUifX0=',
                    new CardContentModel(
                        'alice2',
                        'member',
                        'public-key-2',
                        CardScopes::TYPE_GLOBAL,
                        ['customData' => 'qwerty'],
                        new DeviceInfoModel('iPhone6s', 'Space grey one')
                    ),
                    new SignedResponseMetaModel(
                        ['sign-id-3' => 'X3NpZ24z'],
                        new DateTime('2016-11-04T13:16:17+0000'),
                        'v4'
                    )
                ),
                new CreateCardRequest(
                    'alice2',
                    'member',
                    new Buffer('public-key-2'),
                    CardScopes::TYPE_GLOBAL,
                    ['customData' => 'qwerty'],
                    new DeviceInfoModel('iPhone6s', 'Space grey one')
                ),
                new Card(
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
                    ['sign-id-3' => Buffer::fromBase64('X3NpZ24z')]
                ),
            ],
            [
                new SignedRequestModel(
                    new CardContentModel('alice2', 'member', base64_encode('public-key-2'), CardScopes::TYPE_GLOBAL),
                    new SignedRequestMetaModel(['sign-id-3' => 'X3NpZ24z'])
                ),
                new SignedResponseModel(
                    'model-id-1',
                    'eyJpZGVudGl0eSI6ImFsaWNlMiIsImlkZW50aXR5X3R5cGUiOiJtZW1iZXIiLCJwdWJsaWNfa2V5IjoicHVibGljLWtleS0yIiwic2NvcGUiOiJnbG9iYWwifQ==',
                    new CardContentModel('alice2', 'member', 'public-key-2', CardScopes::TYPE_GLOBAL),
                    new SignedResponseMetaModel(
                        ['sign-id-3' => 'X3NpZ24z'],
                        new DateTime('2016-11-04T13:16:17+0000'),
                        'v4'
                    )
                ),
                new CreateCardRequest('alice2', 'member', new Buffer('public-key-2'), CardScopes::TYPE_GLOBAL),
                new Card(
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
                    ['sign-id-3' => Buffer::fromBase64('X3NpZ24z')]
                ),
            ],
        ];
    }


    public function getSearchDataProvider()
    {
        return [
            [
                new SearchCardRequest('email', CardScopes::TYPE_GLOBAL),
                ['user@virgilsecurity.com', 'another.user@virgilsecurity.com'],
                [
                    new SignedResponseModel(
                        'model-id-1',
                        'eyJpZGVudGl0eSI6ImFsaWNlMiIsImlkZW50aXR5X3R5cGUiOiJtZW1iZXIiLCJwdWJsaWNfa2V5IjoicHVibGljLWtleS0yIiwic2NvcGUiOiJnbG9iYWwifQ==',
                        new CardContentModel('alice2', 'member', 'public-key-2', CardScopes::TYPE_GLOBAL),
                        new SignedResponseMetaModel(
                            ['sign-id-3' => 'X3NpZ24z'],
                            new DateTime('2016-11-04T13:16:17+0000'),
                            'v4'
                        )
                    ),
                    new SignedResponseModel(
                        'model-id-2',
                        'eyJpZGVudGl0eSI6ImFsaWNlMyIsImlkZW50aXR5X3R5cGUiOiJtZW1iZXIiLCJwdWJsaWNfa2V5IjoicHVibGljLWtleS0yIiwiZGF0YSI6eyJjdXN0b21EYXRhIjoicXdlcnR5In0sInNjb3BlIjoiYXBwbGljYXRpb24iLCJpbmZvIjp7ImRldmljZSI6ImlQaG9uZTZzIiwiZGV2aWNlX25hbWUiOiJTcGFjZSBncmV5IG9uZSJ9fQ==',
                        new CardContentModel(
                            'alice3',
                            'member',
                            'public-key-2',
                            CardScopes::TYPE_APPLICATION,
                            ['customData' => 'qwerty'],
                            new DeviceInfoModel('iPhone6s', 'Space grey one')
                        ),
                        new SignedResponseMetaModel(
                            ['sign-id-3' => 'X3NpZ24z'],
                            new DateTime('2016-11-04T13:16:17+0000'),
                            'v4'
                        )
                    ),
                ],
                [
                    new Card(
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
                        ['sign-id-3' => Buffer::fromBase64('X3NpZ24z')]
                    ),
                    new Card(
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
                        ['sign-id-3' => Buffer::fromBase64('X3NpZ24z')]
                    ),
                ],
            ],
        ];
    }
}
