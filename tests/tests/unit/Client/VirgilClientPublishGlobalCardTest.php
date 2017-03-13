<?php
namespace Virgil\Sdk\Tests\Unit\Client;


use DateTime;

use Virgil\Sdk\Buffer;

use Virgil\Sdk\Client\Card;

use Virgil\Sdk\Client\Requests\Constants\CardScopes;
use Virgil\Sdk\Client\Requests\CreateCardRequest;

use Virgil\Sdk\Client\VirgilServices\Model\DeviceInfoModel;
use Virgil\Sdk\Client\VirgilServices\Model\SignedRequestModel;
use Virgil\Sdk\Client\VirgilServices\Model\SignedResponseModel;

use Virgil\Sdk\Client\VirgilServices\Model\ValidationModel;
use Virgil\Sdk\Tests\Unit\Client\Requests\CardRequest;

use Virgil\Sdk\Tests\Unit\Client\VirgilServices\Model\RequestModel;
use Virgil\Sdk\Tests\Unit\Client\VirgilServices\Model\ResponseModel;

use Virgil\Sdk\Tests\Unit\Card as VirgilClientCard;

class VirgilClientPublishGlobalCardTest extends AbstractVirgilClientTest
{
    /**
     * @dataProvider getCreateGlobalCardDataProvider
     *
     * @param SignedRequestModel  $createCardRequestModelArgs
     * @param SignedResponseModel $signedResponseModelArgs
     * @param CreateCardRequest   $createGlobalCardRequestArgs
     * @param Card                $expectedCardArgs
     *
     * @test
     */
    public function publishGlobalCard__withCreateGlobalCardRequest__returnsCard(
        $createGlobalCardRequestArgs,
        $expectedCardArgs,
        $createCardRequestModelArgs,
        $signedResponseModelArgs
    ) {
        $createGlobalCardRequest = CardRequest::createPublishGlobalCardRequest(...$createGlobalCardRequestArgs);

        $expectedCard = VirgilClientCard::createCard($expectedCardArgs);

        $this->configureVirgilServiceResponse($createCardRequestModelArgs, $signedResponseModelArgs);


        $card = $this->virgilClient->publishGlobalCard($createGlobalCardRequest);


        $this->assertEquals($expectedCard, $card);
    }


    public function getCreateGlobalCardDataProvider()
    {
        return [
            [
                [
                    [
                        'alice2',
                        'member',
                        new Buffer('public-key-2'),
                        new ValidationModel(
                            'MIGZMA0GCWCGSAFlAwQCAgUABIGHMIGEAkB0RVkqJ89UlvsbBDgA2nPNVEhRptbF8ZVFXrZGbzSmLU9OLw2A'
                        ),
                        ['customData' => 'qwerty'],
                        new DeviceInfoModel('iPhone6s', 'Space grey one'),
                    ],
                    ['sign-id-3' => 'X3NpZ24z'],
                ],
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
                    ['sign-id-3' => Buffer::fromBase64('X3NpZ24z')],
                    new DateTime('2016-11-04T13:16:17+0000'),
                ],
                [
                    [
                        'alice2',
                        'member',
                        base64_encode('public-key-2'),
                        CardScopes::TYPE_GLOBAL,
                        ['customData' => 'qwerty'],
                        new DeviceInfoModel('iPhone6s', 'Space grey one'),
                    ],
                    [
                        ['sign-id-3' => 'X3NpZ24z'],
                        'MIGZMA0GCWCGSAFlAwQCAgUABIGHMIGEAkB0RVkqJ89UlvsbBDgA2nPNVEhRptbF8ZVFXrZGbzSmLU9OLw2A',
                    ],
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
                        ['sign-id-3' => 'X3NpZ24z'],
                        new DateTime('2016-11-04T13:16:17+0000'),
                        'v4',
                    ],
                ],
            ],
        ];
    }


    protected function configureVirgilServiceResponse($createCardRequestModelRequestArgs, $signedResponseModelArgs)
    {
        $createRequestModel = RequestModel::createCreateCardRequestModel(...$createCardRequestModelRequestArgs);

        $signedResponseModel = ResponseModel::createSignedResponseModel(...$signedResponseModelArgs);

        $this->registrationAuthorityServiceMock->expects($this->once())
                                               ->method('create')
                                               ->with($createRequestModel)
                                               ->willReturn($signedResponseModel)
        ;
    }
}
