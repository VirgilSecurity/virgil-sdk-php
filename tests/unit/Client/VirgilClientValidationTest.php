<?php
namespace Virgil\Tests\Unit\Client;


use PHPUnit\Framework\TestCase;

use Virgil\Sdk\Buffer;
use Virgil\Sdk\Client\Card\CardsService;
use Virgil\Sdk\Client\Card\Model\CardContentModel;
use Virgil\Sdk\Client\Card\Model\DeviceInfoModel;
use Virgil\Sdk\Client\Card\Model\SearchCriteria;
use Virgil\Sdk\Client\Card\Model\SignedRequestMetaModel;
use Virgil\Sdk\Client\Card\Model\SignedRequestModel;
use Virgil\Sdk\Client\Card\Model\SignedResponseMetaModel;
use Virgil\Sdk\Client\Card\Model\SignedResponseModel;
use Virgil\Sdk\Client\Constants\CardScope;
use Virgil\Sdk\Client\Requests\CreateCardRequest;
use Virgil\Sdk\Client\Validator\CardValidator;
use Virgil\Sdk\Client\VirgilClient;
use Virgil\Sdk\Client\VirgilClientParams;
use Virgil\Sdk\Cryptography\VirgilCrypto;

class VirgilClientValidationTest extends TestCase
{
    /**
     * @expectedException \Virgil\Sdk\Client\Validator\CardValidationException
     */
    public function testGetCardValidationFailBecauseOfInvalidSing()
    {
        $cardsServiceResponse = new SignedResponseModel(
            'model-id-1',
            'eyJpZGVudGl0eSI6ImFsaWNlMiIsImlkZW50aXR5X3R5cGUiOiJtZW1iZXIiLCJwdWJsaWNfa2V5IjoicHVibGljLWtleS0yIiwiZGF0YSI6eyJjdXN0b21EYXRhIjoicXdlcnR5In0sInNjb3BlIjoiZ2xvYmFsIiwiaW5mbyI6eyJkZXZpY2UiOiJpUGhvbmU2cyIsImRldmljZV9uYW1lIjoiU3BhY2UgZ3JleSBvbmUifX0=',
            new CardContentModel('alice2', 'member', 'public-key-2', CardScope::TYPE_GLOBAL, ['customData' => 'qwerty'], new DeviceInfoModel('iPhone6s', 'Space grey one')),
            new SignedResponseMetaModel(['sign-id-3' => '_sign3', 'sign-id-4' => '_sign4'], new \DateTime('2016-11-04T13:16:17+0000'), 'v4', 'bb5db5084dab511135ec24c2fdc5ce2bca8f7bf6b0b83a7fa4c3cbdcdc740a59')
        );
        $cardsServiceMock = $this->createPartialMock(CardsService::class, ['get']);
        $cardsServiceMock
            ->expects($this->once())
            ->method('get')
            ->with($cardsServiceResponse->getId())
            ->willReturn($cardsServiceResponse);


        $client = new VirgilClient(new VirgilClientParams('asfja8'), $cardsServiceMock);
        $cardValidator = new CardValidator(new VirgilCrypto());
        $client->setCardValidator($cardValidator);
        $client->getCard($cardsServiceResponse->getId());
    }

    /**
     * @expectedException \Virgil\Sdk\Client\Validator\CardValidationException
     */
    public function testSearchCardValidationFailBecauseOfInvalidSing()
    {
        $sc = new SearchCriteria(['user@virgilsecurity.com', 'another.user@virgilsecurity.com'], 'email', CardScope::TYPE_GLOBAL);
        $response = [
            new SignedResponseModel(
                'model-id-1',
                'eyJpZGVudGl0eSI6ImFsaWNlMiIsImlkZW50aXR5X3R5cGUiOiJtZW1iZXIiLCJwdWJsaWNfa2V5IjoicHVibGljLWtleS0yIiwic2NvcGUiOiJnbG9iYWwifQ==',
                new CardContentModel('alice2', 'member', 'public-key-2', CardScope::TYPE_GLOBAL),
                new SignedResponseMetaModel(['sign-id-3' => '_sign3'], new \DateTime('2016-11-04T13:16:17+0000'), 'v4', 'bb5db5084dab511135ec24c2fdc5ce2bca8f7bf6b0b83a7fa4c3cbdcdc740a59')
            ),
            new SignedResponseModel(
                'model-id-2',
                'eyJpZGVudGl0eSI6ImFsaWNlMyIsImlkZW50aXR5X3R5cGUiOiJtZW1iZXIiLCJwdWJsaWNfa2V5IjoicHVibGljLWtleS0yIiwiZGF0YSI6eyJjdXN0b21EYXRhIjoicXdlcnR5In0sInNjb3BlIjoiYXBwbGljYXRpb24iLCJpbmZvIjp7ImRldmljZSI6ImlQaG9uZTZzIiwiZGV2aWNlX25hbWUiOiJTcGFjZSBncmV5IG9uZSJ9fQ==',
                new CardContentModel('alice3', 'member', 'public-key-2', CardScope::TYPE_APPLICATION, ['customData' => 'qwerty'], new DeviceInfoModel('iPhone6s', 'Space grey one')),
                new SignedResponseMetaModel(['sign-id-3' => '_sign3'], new \DateTime('2016-11-04T13:16:17+0000'), 'v4', 'bb5db5084dab511135ec24c2fdc5ce2bca8f7bf6b0b83a7fa4c3cbdcdc740a59')
            ),
        ];

        $cardsServiceMock = $this->createPartialMock(CardsService::class, ['search']);
        $cardsServiceMock
            ->expects($this->once())
            ->method('search')
            ->with($sc)
            ->willReturn($response);


        $client = new VirgilClient(new VirgilClientParams('asfja8'), $cardsServiceMock);
        $cardValidator = new CardValidator(new VirgilCrypto());
        $client->setCardValidator($cardValidator);
        $client->searchCards($sc);
    }

    /**
     * @expectedException \Virgil\Sdk\Client\Validator\CardValidationException
     */
    public function testCreateCardValidationFailBecauseOfInvalidSing()
    {
        $request = new SignedRequestModel(
            new CardContentModel('alice2', 'member', base64_encode('public-key-2'), CardScope::TYPE_GLOBAL, ['customData' => 'qwerty'], new DeviceInfoModel('iPhone6s', 'Space grey one')),
            new SignedRequestMetaModel(['sign-id-3' => 'X3NpZ24z'])
        );

        $response = new SignedResponseModel(
            'model-id-1',
            'eyJpZGVudGl0eSI6ImFsaWNlMiIsImlkZW50aXR5X3R5cGUiOiJtZW1iZXIiLCJwdWJsaWNfa2V5IjoicHVibGljLWtleS0yIiwiZGF0YSI6eyJjdXN0b21EYXRhIjoicXdlcnR5In0sInNjb3BlIjoiZ2xvYmFsIiwiaW5mbyI6eyJkZXZpY2UiOiJpUGhvbmU2cyIsImRldmljZV9uYW1lIjoiU3BhY2UgZ3JleSBvbmUifX0=',
            new CardContentModel('alice2', 'member', 'public-key-2', CardScope::TYPE_GLOBAL, ['customData' => 'qwerty'], new DeviceInfoModel('iPhone6s', 'Space grey one')),
            new SignedResponseMetaModel(['sign-id-3' => 'X3NpZ24z'], new \DateTime('2016-11-04T13:16:17+0000'), 'v4', 'bb5db5084dab511135ec24c2fdc5ce2bca8f7bf6b0b83a7fa4c3cbdcdc740a59')
        );

        $cardRequest = new CreateCardRequest('alice2', 'member', new Buffer('public-key-2'), CardScope::TYPE_GLOBAL, ['customData' => 'qwerty'], new DeviceInfoModel('iPhone6s', 'Space grey one'));

        $cardRequest->appendSignature('sign-id-3', Buffer::fromBase64('X3NpZ24z'));

        $cardsServiceMock = $this->createPartialMock(CardsService::class, ['create']);
        $cardsServiceMock
            ->expects($this->once())
            ->method('create')
            ->with($request)
            ->willReturn($response);


        $client = new VirgilClient(new VirgilClientParams('asfja8'), $cardsServiceMock);
        $cardValidator = new CardValidator(new VirgilCrypto());
        $client->setCardValidator($cardValidator);
        $client->createCard($cardRequest);
    }
}
