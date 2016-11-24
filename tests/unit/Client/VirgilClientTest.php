<?php

namespace Virgil\Tests\Unit\Client;


use PHPUnit\Framework\TestCase;
use Virgil\SDK\Buffer;
use Virgil\SDK\Client\Card;
use Virgil\SDK\Client\Card\CardsService;
use Virgil\SDK\Client\Card\Model\CardContentModel;
use Virgil\SDK\Client\Card\Model\DeviceInfoModel;
use Virgil\SDK\Client\Card\Model\RevokeCardContentModel;
use Virgil\SDK\Client\Card\Model\SearchCriteria;
use Virgil\SDK\Client\Card\Model\SignedRequestMetaModel;
use Virgil\SDK\Client\Card\Model\SignedRequestModel;
use Virgil\SDK\Client\Card\Model\SignedResponseMetaModel;
use Virgil\SDK\Client\Card\Model\SignedResponseModel;
use Virgil\SDK\Client\CardScope;
use Virgil\SDK\Client\CreateCardRequest;
use Virgil\SDK\Client\RevocationReason;
use Virgil\SDK\Client\RevokeCardRequest;
use Virgil\SDK\Client\VirgilClient;
use Virgil\SDK\Client\VirgilClientParams;

class VirgilClientTest extends TestCase
{
    /**
     * @dataProvider getCardDataProvider
     * @param $cardsServiceResponse
     * @param $expectedCard
     */
    public function testGetCard(SignedResponseModel $cardsServiceResponse, Card $expectedCard)
    {
        $cardsServiceMock = $this->createPartialMock(CardsService::class, ['get']);
        $cardsServiceMock
            ->expects($this->once())
            ->method('get')
            ->with($cardsServiceResponse->getId())
            ->willReturn($cardsServiceResponse);

        $virgilClientParams = new VirgilClientParams('asfja8');
        $client = new VirgilClient($virgilClientParams, $cardsServiceMock);
        $card = $client->getCard($cardsServiceResponse->getId());

        $this->assertEquals($expectedCard, $card);
    }

    /**
     * @dataProvider getRevokeDataProvider
     * @param SignedRequestModel $requestModel
     * @param RevokeCardRequest $revokeCardRequest
     */
    public function testRevokeCard(SignedRequestModel $requestModel, RevokeCardRequest $revokeCardRequest)
    {
        /** @var SignedRequestMetaModel $meta */
        $meta = $requestModel->getMeta();
        foreach ($meta->getSigns() as $signKey => $sign) {
            $revokeCardRequest->appendSignature($signKey, Buffer::fromBase64($sign));
        }

        $cardsServiceMock = $this->createPartialMock(CardsService::class, ['delete']);
        $cardsServiceMock
            ->expects($this->once())
            ->method('delete')
            ->with($requestModel)
            ->willReturn([]);

        $virgilClientParams = new VirgilClientParams('asfja8');
        $client = new VirgilClient($virgilClientParams, $cardsServiceMock);
        $client->revokeCard($revokeCardRequest);
    }

    /**
     * @dataProvider getCreateDataProvider
     * @param SignedRequestModel $requestModel
     * @param SignedResponseModel $responseModel
     * @param CreateCardRequest $createCardRequest
     * @param Card $expectedCard
     */
    public function testCreateCard(
        SignedRequestModel $requestModel, SignedResponseModel $responseModel, CreateCardRequest $createCardRequest, Card $expectedCard
    )
    {
        /** @var SignedRequestMetaModel $meta */
        $meta = $requestModel->getMeta();
        foreach ($meta->getSigns() as $signKey => $sign) {
            $createCardRequest->appendSignature($signKey, Buffer::fromBase64($sign));
        }

        $cardsServiceMock = $this->createPartialMock(CardsService::class, ['create']);
        $cardsServiceMock
            ->expects($this->once())
            ->method('create')
            ->with($requestModel)
            ->willReturn($responseModel);

        $virgilClientParams = new VirgilClientParams('asfja8');
        $client = new VirgilClient($virgilClientParams, $cardsServiceMock);
        $card = $client->createCard($createCardRequest);
        $this->assertEquals($expectedCard, $card);
    }

    /**
     * @dataProvider getSearchDataProvider
     * @param SearchCriteria $searchCriteria
     * @param array $responseModels
     * @param array $expectedCards
     */
    public function testSearchCard(SearchCriteria $searchCriteria, array $responseModels, array $expectedCards)
    {
        $cardsServiceMock = $this->createPartialMock(CardsService::class, ['search']);
        $cardsServiceMock
            ->expects($this->once())
            ->method('search')
            ->with($searchCriteria)
            ->willReturn($responseModels);

        $virgilClientParams = new VirgilClientParams('asfja8');
        $client = new VirgilClient($virgilClientParams, $cardsServiceMock);
        $cards = $client->searchCards($searchCriteria);
        $this->assertEquals($expectedCards, $cards);
    }

    public function getRevokeDataProvider()
    {
        return [
            [
                new SignedRequestModel(
                    new RevokeCardContentModel('model-id-1', RevocationReason::UNSPECIFIED_TYPE),
                    new SignedRequestMetaModel(['sign-id-3' => 'X3NpZ24z', 'sign-id-4' => 'X3NpZ240'])
                ), new RevokeCardRequest('model-id-1', RevocationReason::UNSPECIFIED_TYPE)
            ],
            [
                new SignedRequestModel(
                    new RevokeCardContentModel('model-id-2', RevocationReason::COMPROMISED_TYPE),
                    new SignedRequestMetaModel(['sign-id-4' => 'X3NpZ240'])
                ), new RevokeCardRequest('model-id-2', RevocationReason::COMPROMISED_TYPE)
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
                    new CardContentModel('alice2', 'member', 'public-key-2', CardScope::TYPE_GLOBAL, ['customData' => 'qwerty'], new DeviceInfoModel('iPhone6s', 'Space grey one')),
                    new SignedResponseMetaModel(['sign-id-3' => 'X3NpZ24z', 'sign-id-4' => 'X3NpZ240'], new \DateTime('2016-11-04T13:16:17+0000'), 'v4', 'bb5db5084dab511135ec24c2fdc5ce2bca8f7bf6b0b83a7fa4c3cbdcdc740a59')
                ),
                new Card(
                    'model-id-1',
                    Buffer::fromBase64('eyJpZGVudGl0eSI6ImFsaWNlMiIsImlkZW50aXR5X3R5cGUiOiJtZW1iZXIiLCJwdWJsaWNfa2V5IjoicHVibGljLWtleS0yIiwiZGF0YSI6eyJjdXN0b21EYXRhIjoicXdlcnR5In0sInNjb3BlIjoiZ2xvYmFsIiwiaW5mbyI6eyJkZXZpY2UiOiJpUGhvbmU2cyIsImRldmljZV9uYW1lIjoiU3BhY2UgZ3JleSBvbmUifX0='),
                    'alice2', 'member', Buffer::fromBase64('public-key-2'), CardScope::TYPE_GLOBAL, ['customData' => 'qwerty'],
                    'iPhone6s', 'Space grey one', 'v4', ['sign-id-3' => Buffer::fromBase64('X3NpZ24z'), 'sign-id-4' => Buffer::fromBase64('X3NpZ240')]
                ),
            ],
            [
                new SignedResponseModel(
                    'model-id-2',
                    'eyJpZGVudGl0eSI6ImFsaWNlMiIsImlkZW50aXR5X3R5cGUiOiJtZW1iZXIiLCJwdWJsaWNfa2V5IjoicHVibGljLWtleS0yIiwic2NvcGUiOiJhcHBsaWNhdGlvbiJ9',
                    new CardContentModel('alice2', 'member', 'public-key-2', CardScope::TYPE_APPLICATION),
                    new SignedResponseMetaModel(['sign-id-3' => 'X3NpZ24z', 'sign-id-4' => 'X3NpZ240'], new \DateTime('2016-11-04T13:16:17+0000'), 'v4', 'bb5db5084dab511135ec24c2fdc5ce2bca8f7bf6b0b83a7fa4c3cbdcdc740a59')
                ),
                new Card(
                    'model-id-2',
                    Buffer::fromBase64('eyJpZGVudGl0eSI6ImFsaWNlMiIsImlkZW50aXR5X3R5cGUiOiJtZW1iZXIiLCJwdWJsaWNfa2V5IjoicHVibGljLWtleS0yIiwic2NvcGUiOiJhcHBsaWNhdGlvbiJ9'),
                    'alice2', 'member', Buffer::fromBase64('public-key-2'), CardScope::TYPE_APPLICATION, [], null, null, 'v4',
                    ['sign-id-3' => Buffer::fromBase64('X3NpZ24z'), 'sign-id-4' => Buffer::fromBase64('X3NpZ240')])
            ]
        ];
    }

    public function getCreateDataProvider()
    {
        return [
            [
                new SignedRequestModel(
                    new CardContentModel('alice2', 'member', base64_encode('public-key-2'), CardScope::TYPE_GLOBAL, ['customData' => 'qwerty'], new DeviceInfoModel('iPhone6s', 'Space grey one')),
                    new SignedRequestMetaModel(['sign-id-3' => 'X3NpZ24z'])
                ),
                new SignedResponseModel(
                    'model-id-1',
                    'eyJpZGVudGl0eSI6ImFsaWNlMiIsImlkZW50aXR5X3R5cGUiOiJtZW1iZXIiLCJwdWJsaWNfa2V5IjoicHVibGljLWtleS0yIiwiZGF0YSI6eyJjdXN0b21EYXRhIjoicXdlcnR5In0sInNjb3BlIjoiZ2xvYmFsIiwiaW5mbyI6eyJkZXZpY2UiOiJpUGhvbmU2cyIsImRldmljZV9uYW1lIjoiU3BhY2UgZ3JleSBvbmUifX0=',
                    new CardContentModel('alice2', 'member', 'public-key-2', CardScope::TYPE_GLOBAL, ['customData' => 'qwerty'], new DeviceInfoModel('iPhone6s', 'Space grey one')),
                    new SignedResponseMetaModel(['sign-id-3' => 'X3NpZ24z'], new \DateTime('2016-11-04T13:16:17+0000'), 'v4', 'bb5db5084dab511135ec24c2fdc5ce2bca8f7bf6b0b83a7fa4c3cbdcdc740a59')
                ),
                new CreateCardRequest('alice2', 'member', new Buffer('public-key-2'), CardScope::TYPE_GLOBAL, ['customData' => 'qwerty'], new DeviceInfoModel('iPhone6s', 'Space grey one')),
                new Card(
                    'model-id-1',
                    Buffer::fromBase64('eyJpZGVudGl0eSI6ImFsaWNlMiIsImlkZW50aXR5X3R5cGUiOiJtZW1iZXIiLCJwdWJsaWNfa2V5IjoicHVibGljLWtleS0yIiwiZGF0YSI6eyJjdXN0b21EYXRhIjoicXdlcnR5In0sInNjb3BlIjoiZ2xvYmFsIiwiaW5mbyI6eyJkZXZpY2UiOiJpUGhvbmU2cyIsImRldmljZV9uYW1lIjoiU3BhY2UgZ3JleSBvbmUifX0='),
                    'alice2', 'member', Buffer::fromBase64('public-key-2'), CardScope::TYPE_GLOBAL, ['customData' => 'qwerty'], 'iPhone6s', 'Space grey one', 'v4', ['sign-id-3' => Buffer::fromBase64('X3NpZ24z')]
                )
            ],
            [
                new SignedRequestModel(
                    new CardContentModel('alice2', 'member', base64_encode('public-key-2'), CardScope::TYPE_GLOBAL),
                    new SignedRequestMetaModel(['sign-id-3' => 'X3NpZ24z'])
                ),
                new SignedResponseModel(
                    'model-id-1',
                    'eyJpZGVudGl0eSI6ImFsaWNlMiIsImlkZW50aXR5X3R5cGUiOiJtZW1iZXIiLCJwdWJsaWNfa2V5IjoicHVibGljLWtleS0yIiwic2NvcGUiOiJnbG9iYWwifQ==',
                    new CardContentModel('alice2', 'member', 'public-key-2', CardScope::TYPE_GLOBAL),
                    new SignedResponseMetaModel(['sign-id-3' => 'X3NpZ24z'], new \DateTime('2016-11-04T13:16:17+0000'), 'v4', 'bb5db5084dab511135ec24c2fdc5ce2bca8f7bf6b0b83a7fa4c3cbdcdc740a59')
                ),
                new CreateCardRequest('alice2', 'member', new Buffer('public-key-2'), CardScope::TYPE_GLOBAL),
                new Card(
                    'model-id-1',
                    Buffer::fromBase64('eyJpZGVudGl0eSI6ImFsaWNlMiIsImlkZW50aXR5X3R5cGUiOiJtZW1iZXIiLCJwdWJsaWNfa2V5IjoicHVibGljLWtleS0yIiwic2NvcGUiOiJnbG9iYWwifQ=='),
                    'alice2', 'member', Buffer::fromBase64('public-key-2'), CardScope::TYPE_GLOBAL, [], null, null, 'v4', ['sign-id-3' => Buffer::fromBase64('X3NpZ24z')]
                )
            ]
        ];
    }

    public function getSearchDataProvider()
    {
        return [
            [
                new SearchCriteria(['user@virgilsecurity.com', 'another.user@virgilsecurity.com'], 'email', CardScope::TYPE_GLOBAL),
                [
                    new SignedResponseModel(
                        'model-id-1',
                        'eyJpZGVudGl0eSI6ImFsaWNlMiIsImlkZW50aXR5X3R5cGUiOiJtZW1iZXIiLCJwdWJsaWNfa2V5IjoicHVibGljLWtleS0yIiwic2NvcGUiOiJnbG9iYWwifQ==',
                        new CardContentModel('alice2', 'member', 'public-key-2', CardScope::TYPE_GLOBAL),
                        new SignedResponseMetaModel(['sign-id-3' => 'X3NpZ24z'], new \DateTime('2016-11-04T13:16:17+0000'), 'v4', 'bb5db5084dab511135ec24c2fdc5ce2bca8f7bf6b0b83a7fa4c3cbdcdc740a59')
                    ),
                    new SignedResponseModel(
                        'model-id-2',
                        'eyJpZGVudGl0eSI6ImFsaWNlMyIsImlkZW50aXR5X3R5cGUiOiJtZW1iZXIiLCJwdWJsaWNfa2V5IjoicHVibGljLWtleS0yIiwiZGF0YSI6eyJjdXN0b21EYXRhIjoicXdlcnR5In0sInNjb3BlIjoiYXBwbGljYXRpb24iLCJpbmZvIjp7ImRldmljZSI6ImlQaG9uZTZzIiwiZGV2aWNlX25hbWUiOiJTcGFjZSBncmV5IG9uZSJ9fQ==',
                        new CardContentModel('alice3', 'member', 'public-key-2', CardScope::TYPE_APPLICATION, ['customData' => 'qwerty'], new DeviceInfoModel('iPhone6s', 'Space grey one')),
                        new SignedResponseMetaModel(['sign-id-3' => 'X3NpZ24z'], new \DateTime('2016-11-04T13:16:17+0000'), 'v4', 'bb5db5084dab511135ec24c2fdc5ce2bca8f7bf6b0b83a7fa4c3cbdcdc740a59')
                    ),
                ],
                [
                    new Card(
                        'model-id-1',
                        Buffer::fromBase64('eyJpZGVudGl0eSI6ImFsaWNlMiIsImlkZW50aXR5X3R5cGUiOiJtZW1iZXIiLCJwdWJsaWNfa2V5IjoicHVibGljLWtleS0yIiwic2NvcGUiOiJnbG9iYWwifQ=='),
                        'alice2', 'member', Buffer::fromBase64('public-key-2'), CardScope::TYPE_GLOBAL, [], null, null, 'v4', ['sign-id-3' => Buffer::fromBase64('X3NpZ24z')]
                    ),
                    new Card(
                        'model-id-2',
                        Buffer::fromBase64('eyJpZGVudGl0eSI6ImFsaWNlMyIsImlkZW50aXR5X3R5cGUiOiJtZW1iZXIiLCJwdWJsaWNfa2V5IjoicHVibGljLWtleS0yIiwiZGF0YSI6eyJjdXN0b21EYXRhIjoicXdlcnR5In0sInNjb3BlIjoiYXBwbGljYXRpb24iLCJpbmZvIjp7ImRldmljZSI6ImlQaG9uZTZzIiwiZGV2aWNlX25hbWUiOiJTcGFjZSBncmV5IG9uZSJ9fQ=='),
                        'alice3', 'member', Buffer::fromBase64('public-key-2'), CardScope::TYPE_APPLICATION, ['customData' => 'qwerty'], 'iPhone6s', 'Space grey one', 'v4', ['sign-id-3' => Buffer::fromBase64('X3NpZ24z')]
                    )
                ]
            ]
        ];
    }
}