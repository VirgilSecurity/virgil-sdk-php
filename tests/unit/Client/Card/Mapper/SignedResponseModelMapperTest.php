<?php

namespace Virgil\Tests\Unit\Client\Card\Mapper;


use PHPUnit\Framework\TestCase;
use Virgil\SDK\Client\Card\Mapper\SignedResponseModelMapper;
use Virgil\SDK\Client\Card\Model\CardContentModel;
use Virgil\SDK\Client\Card\Model\DeviceInfoModel;
use Virgil\SDK\Client\Card\Model\SignedResponseMetaModel;
use Virgil\SDK\Client\Card\Model\SignedResponseModel;
use Virgil\SDK\Client\CardScope;

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
                    new CardContentModel('alice2', 'member', 'public-key-2', CardScope::TYPE_GLOBAL, ['customData' => 'qwerty'], new DeviceInfoModel('iPhone6s', 'Space grey one')),
                    new SignedResponseMetaModel(['sign-id-3' => '_sign3', 'sign-id-4' => '_sign4'], new \DateTime('2016-11-04T13:16:17+0000'), 'v4', 'bb5db5084dab511135ec24c2fdc5ce2bca8f7bf6b0b83a7fa4c3cbdcdc740a59')
                ),
                '{"id":"model-id-1","content_snapshot":"eyJpZGVudGl0eSI6ImFsaWNlMiIsImlkZW50aXR5X3R5cGUiOiJtZW1iZXIiLCJwdWJsaWNfa2V5IjoicHVibGljLWtleS0yIiwiZGF0YSI6eyJjdXN0b21EYXRhIjoicXdlcnR5In0sInNjb3BlIjoiZ2xvYmFsIiwiaW5mbyI6eyJkZXZpY2UiOiJpUGhvbmU2cyIsImRldmljZV9uYW1lIjoiU3BhY2UgZ3JleSBvbmUifX0=","meta":{"fingerprint":"bb5db5084dab511135ec24c2fdc5ce2bca8f7bf6b0b83a7fa4c3cbdcdc740a59","created_at":"2016-11-04T13:16:17+0000","card_version":"v4","signs":{"sign-id-3":"_sign3","sign-id-4":"_sign4"}}}'
            ],
            [
                new SignedResponseModel(
                    'model-id-2',
                    new CardContentModel('alice2', 'member', 'public-key-2', CardScope::TYPE_GLOBAL),
                    new SignedResponseMetaModel(['sign-id-3' => '_sign3', 'sign-id-4' => '_sign4'], new \DateTime('2016-11-04T13:16:17+0000'), 'v4', 'bb5db5084dab511135ec24c2fdc5ce2bca8f7bf6b0b83a7fa4c3cbdcdc740a59')
                ),
                '{"id":"model-id-2","content_snapshot":"eyJpZGVudGl0eSI6ImFsaWNlMiIsImlkZW50aXR5X3R5cGUiOiJtZW1iZXIiLCJwdWJsaWNfa2V5IjoicHVibGljLWtleS0yIiwic2NvcGUiOiJnbG9iYWwifQ==","meta":{"fingerprint":"bb5db5084dab511135ec24c2fdc5ce2bca8f7bf6b0b83a7fa4c3cbdcdc740a59","created_at":"2016-11-04T13:16:17+0000","card_version":"v4","signs":{"sign-id-3":"_sign3","sign-id-4":"_sign4"}}}'
            ]
        ];
    }
}