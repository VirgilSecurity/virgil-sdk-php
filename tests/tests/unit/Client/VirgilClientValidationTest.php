<?php
namespace Virgil\Sdk\Tests\Unit\Client;


use DateTime;

use Virgil\Sdk\Tests\BaseTestCase;

use Virgil\Sdk\Buffer;

use Virgil\Sdk\Cryptography\VirgilCrypto;

use Virgil\Sdk\Client\Requests\SearchCardRequest;

use Virgil\Sdk\Client\VirgilServices\VirgilCards\CardsService;

use Virgil\Sdk\Client\VirgilServices\VirgilCards\Model\CardContentModel;
use Virgil\Sdk\Client\VirgilServices\VirgilCards\Model\DeviceInfoModel;
use Virgil\Sdk\Client\VirgilServices\VirgilCards\Model\SignedResponseMetaModel;
use Virgil\Sdk\Client\VirgilServices\VirgilCards\Model\SignedResponseModel;

use Virgil\Sdk\Client\Requests\Constants\CardScopes;
use Virgil\Sdk\Client\Requests\CreateCardRequest;

use Virgil\Sdk\Client\VirgilClient;
use Virgil\Sdk\Client\VirgilClientParams;

use Virgil\Sdk\Client\Validator\CardValidator;

class VirgilClientValidationTest extends BaseTestCase
{
    const VIRGIL_CARDS_ACCESS_TOKEN = 'asfja8';


    /**
     * @expectedException \Virgil\Sdk\Client\Validator\CardValidationException
     *
     * @test
     */
    public function getCard__withInvalidSign__throwsExceptionWhenValidatorSet()
    {
        $getCardResponse = new SignedResponseModel(
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
                ['sign-id-3' => '_sign3', 'sign-id-4' => '_sign4'], new DateTime('2016-11-04T13:16:17+0000'), 'v4'
            )
        );

        $cardsServiceMock = $this->createPartialMock(CardsService::class, ['get']);
        $cardsServiceMock->expects($this->once())
                         ->method('get')
                         ->with($getCardResponse->getId())
                         ->willReturn($getCardResponse)
        ;


        $virgilClientWithValidator = $this->createVirgilClientWithValidator($cardsServiceMock);


        $virgilClientWithValidator->getCard($getCardResponse->getId());


        //expected exception
    }


    /**
     * @expectedException \Virgil\Sdk\Client\Validator\CardValidationException
     *
     * @test
     */
    public function searchCards__withInvalidSign__throwsExceptionWhenValidatorSet()
    {
        $searchCardRequest = new SearchCardRequest('email', CardScopes::TYPE_GLOBAL);

        $searchCardRequest->appendIdentity('user@virgilsecurity.com')
                          ->appendIdentity('another.user@virgilsecurity.com')
        ;

        $searchCardResponse = [
            new SignedResponseModel(
                'model-id-1',
                'eyJpZGVudGl0eSI6ImFsaWNlMiIsImlkZW50aXR5X3R5cGUiOiJtZW1iZXIiLCJwdWJsaWNfa2V5IjoicHVibGljLWtleS0yIiwic2NvcGUiOiJnbG9iYWwifQ==',
                new CardContentModel('alice2', 'member', 'public-key-2', CardScopes::TYPE_GLOBAL),
                new SignedResponseMetaModel(['sign-id-3' => '_sign3'], new DateTime('2016-11-04T13:16:17+0000'), 'v4')
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
                new SignedResponseMetaModel(['sign-id-3' => '_sign3'], new DateTime('2016-11-04T13:16:17+0000'), 'v4')
            ),
        ];

        $cardsServiceMock = $this->createPartialMock(CardsService::class, ['search']);
        $cardsServiceMock->expects($this->once())
                         ->method('search')
                         ->with($searchCardRequest->getRequestModel())
                         ->willReturn($searchCardResponse)
        ;


        $virgilClientWithValidator = $this->createVirgilClientWithValidator($cardsServiceMock);


        $virgilClientWithValidator->searchCards($searchCardRequest);


        //expected exception
    }


    /**
     * @expectedException \Virgil\Sdk\Client\Validator\CardValidationException
     *
     * @test
     */
    public function createCard__withInvalidSign__throwsExceptionWhenValidatorSet()
    {
        $createCardRequest = new CreateCardRequest(
            'alice2',
            'member',
            new Buffer('public-key-2'),
            CardScopes::TYPE_GLOBAL,
            ['customData' => 'qwerty'],
            new DeviceInfoModel('iPhone6s', 'Space grey one')
        );

        $createCardResponse = new SignedResponseModel(
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
            new SignedResponseMetaModel(['sign-id-3' => 'X3NpZ24z'], new DateTime('2016-11-04T13:16:17+0000'), 'v4')
        );

        $createCardRequest->appendSignature('sign-id-3', Buffer::fromBase64('X3NpZ24z'));

        $cardsServiceMock = $this->createPartialMock(CardsService::class, ['create']);

        $cardsServiceMock->expects($this->once())
                         ->method('create')
                         ->with($createCardRequest->getRequestModel())
                         ->willReturn($createCardResponse)
        ;


        $virgilClientWithValidator = $this->createVirgilClientWithValidator($cardsServiceMock);


        $virgilClientWithValidator->createCard($createCardRequest);


        //expected exception
    }


    private function createCardValidator()
    {
        return new CardValidator(new VirgilCrypto());
    }


    private function createVirgilClientWithValidator($cardsServiceMock)
    {
        $virgilClient = new VirgilClient(new VirgilClientParams(self::VIRGIL_CARDS_ACCESS_TOKEN), $cardsServiceMock);
        $cardValidator = $this->createCardValidator();
        $virgilClient->setCardValidator($cardValidator);

        return $virgilClient;
    }
}
