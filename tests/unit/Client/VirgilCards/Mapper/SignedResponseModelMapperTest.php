<?php
namespace Virgil\Tests\Unit\Client\VirgilCards\Mapper;


use DateTime;
use PHPUnit\Framework\TestCase;

use Virgil\Sdk\Client\VirgilCards\Mapper\SignedResponseModelMapper;
use Virgil\Sdk\Client\VirgilCards\Model\CardContentModel;
use Virgil\Sdk\Client\VirgilCards\Model\DeviceInfoModel;
use Virgil\Sdk\Client\VirgilCards\Model\SignedResponseMetaModel;
use Virgil\Sdk\Client\VirgilCards\Model\SignedResponseModel;
use Virgil\Sdk\Client\Requests\Constants\CardScopes;

class SignedResponseModelMapperTest extends TestCase
{
    /**
     * @dataProvider signedResponseDataProvider
     * @param SignedResponseModel $expectedModel
     * @param string $modelJson
     */
    public function testMapSignedResponseJsonToModel($expectedModel, $modelJson)
    {
        $mapper = new SignedResponseModelMapper();
        $this->assertEquals($expectedModel, $mapper->toModel($modelJson));
    }

    /**
     * @dataProvider signedResponseDataProvider
     * @expectedException \RuntimeException
     * @param $model
     * @param $expectedJson
     */
    public function testMapSignedRequestModelToJson($model, $expectedJson)
    {
        $mapper = new SignedResponseModelMapper();
        $this->assertEquals($expectedJson, $mapper->toJson($model));
    }

    public function signedResponseDataProvider()
    {
        return [
            [
                new SignedResponseModel(
                    'model-id-1',
                    'eyJpZGVudGl0eSI6ImFsaWNlMiIsImlkZW50aXR5X3R5cGUiOiJtZW1iZXIiLCJwdWJsaWNfa2V5IjoicHVibGljLWtleS0yIiwiZGF0YSI6eyJjdXN0b21EYXRhIjoicXdlcnR5In0sInNjb3BlIjoiZ2xvYmFsIiwiaW5mbyI6eyJkZXZpY2UiOiJpUGhvbmU2cyIsImRldmljZV9uYW1lIjoiU3BhY2UgZ3JleSBvbmUifX0=',
                    new CardContentModel('alice2', 'member', 'public-key-2', CardScopes::TYPE_GLOBAL, ['customData' => 'qwerty'], new DeviceInfoModel('iPhone6s', 'Space grey one')),
                    new SignedResponseMetaModel(['sign-id-3' => '_sign3', 'sign-id-4' => '_sign4'], new DateTime('2016-11-04T13:16:17+0000'), 'v4')
                ),
                '{"id":"model-id-1","content_snapshot":"eyJpZGVudGl0eSI6ImFsaWNlMiIsImlkZW50aXR5X3R5cGUiOiJtZW1iZXIiLCJwdWJsaWNfa2V5IjoicHVibGljLWtleS0yIiwiZGF0YSI6eyJjdXN0b21EYXRhIjoicXdlcnR5In0sInNjb3BlIjoiZ2xvYmFsIiwiaW5mbyI6eyJkZXZpY2UiOiJpUGhvbmU2cyIsImRldmljZV9uYW1lIjoiU3BhY2UgZ3JleSBvbmUifX0=","meta":{"created_at":"2016-11-04T13:16:17+0000","card_version":"v4","signs":{"sign-id-3":"_sign3","sign-id-4":"_sign4"}}}'
            ],
            [
                new SignedResponseModel(
                    'model-id-2',
                    'eyJpZGVudGl0eSI6ImFsaWNlMiIsImlkZW50aXR5X3R5cGUiOiJtZW1iZXIiLCJwdWJsaWNfa2V5IjoicHVibGljLWtleS0yIiwic2NvcGUiOiJnbG9iYWwifQ==',
                    new CardContentModel('alice2', 'member', 'public-key-2', CardScopes::TYPE_GLOBAL),
                    new SignedResponseMetaModel(['sign-id-3' => '_sign3', 'sign-id-4' => '_sign4'], new DateTime('2016-11-04T13:16:17+0000'), 'v4')
                ),
                '{"id":"model-id-2","content_snapshot":"eyJpZGVudGl0eSI6ImFsaWNlMiIsImlkZW50aXR5X3R5cGUiOiJtZW1iZXIiLCJwdWJsaWNfa2V5IjoicHVibGljLWtleS0yIiwic2NvcGUiOiJnbG9iYWwifQ==","meta":{"created_at":"2016-11-04T13:16:17+0000","card_version":"v4","signs":{"sign-id-3":"_sign3","sign-id-4":"_sign4"}}}'
            ]
        ];
    }
}
