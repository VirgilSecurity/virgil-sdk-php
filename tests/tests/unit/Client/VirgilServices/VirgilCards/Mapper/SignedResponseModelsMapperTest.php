<?php
namespace Virgil\Sdk\Tests\Unit\Client\VirgilServices\VirgilCards\Mapper;


use DateTime;

use Virgil\Sdk\Client\Requests\Constants\CardScopes;

use Virgil\Sdk\Client\VirgilServices\VirgilCards\Mapper\CardContentModelMapper;
use Virgil\Sdk\Client\VirgilServices\VirgilCards\Mapper\SignedResponseModelsMapper;
use Virgil\Sdk\Client\VirgilServices\VirgilCards\Mapper\SignedResponseModelMapper;

use Virgil\Sdk\Client\VirgilServices\VirgilCards\Model\DeviceInfoModel;

use Virgil\Sdk\Tests\Unit\Client\VirgilServices\VirgilCards\Model\ResponseModel;

class SignedResponseModelsMapperTest extends AbstractMapperTest
{
    /**
     * @dataProvider signedResponseModelsDataProvider
     *
     * @param $signedResponseModelsDataSet
     * @param $signedResponseModelsJsonDataSet
     *
     * @test
     */
    public function toModel__fromSignedResponseModelsJsonString__returnsValidSignedResponseModels(
        $signedResponseModelsDataSet,
        $signedResponseModelsJsonDataSet
    ) {
        $expectedSignedResponseModels = ResponseModel::createSignedResponseModels($signedResponseModelsDataSet);

        $signedResponseModelsJson = $this->createSignedResponseModelsJson(...$signedResponseModelsJsonDataSet);


        $signedResponseModels = $this->mapper->toModel($signedResponseModelsJson);


        $this->assertEquals($expectedSignedResponseModels, $signedResponseModels);
    }


    public function signedResponseModelsDataProvider()
    {
        return [
            [
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
                    '[%s,%s]',
                    [
                        [
                            self::CARD_SIGNED_RESPONSE_JSON_FORMAT,
                            'model-id-1',
                            '{"identity":"alice2","identity_type":"member","public_key":"public-key-2","data":{"customData":"qwerty"},"scope":"global","info":{"device":"iPhone6s","device_name":"Space grey one"}}',
                            '{"created_at":"2016-11-04T13:16:17+0000","card_version":"v4","signs":{"sign-id-3":"_sign3","sign-id-4":"_sign4"}}',
                        ],
                        [
                            self::CARD_SIGNED_RESPONSE_JSON_FORMAT,
                            'model-id-2',
                            '{"identity":"alice2","identity_type":"member","public_key":"public-key-2","scope":"global"}',
                            '{"created_at":"2016-11-04T13:16:17+0000","card_version":"v4","signs":{"sign-id-3":"_sign3","sign-id-4":"_sign4"}}',
                        ],
                    ],
                ],
            ],
        ];
    }


    protected function getMapper()
    {
        return new SignedResponseModelsMapper(new SignedResponseModelMapper(new CardContentModelMapper()));
    }


    private function createSignedResponseModelsJson($format, $signedResponseModelsJsonDataSet)
    {
        $signedResponseModelJsonDataSetToString = function ($signedResponseModelJsonData) {
            return $this->createSignedCardResponseJson(...$signedResponseModelJsonData);
        };

        $result = [];

        foreach ($signedResponseModelsJsonDataSet as $signedResponseModelJsonData) {
            $result[] = call_user_func($signedResponseModelJsonDataSetToString, $signedResponseModelJsonData);
        }


        return vsprintf($format, $result);
    }
}
