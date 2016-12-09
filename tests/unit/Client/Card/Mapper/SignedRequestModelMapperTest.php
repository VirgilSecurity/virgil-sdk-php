<?php
namespace Virgil\Tests\Unit\Client\Card\Mapper;


use PHPUnit\Framework\TestCase;

use Virgil\Sdk\Client\Card\Mapper\CreateRequestModelMapper;
use Virgil\Sdk\Client\Card\Mapper\RevokeRequestModelMapper;
use Virgil\Sdk\Client\Card\Mapper\SignedRequestModelMapper;
use Virgil\Sdk\Client\Card\Model\CardContentModel;
use Virgil\Sdk\Client\Card\Model\DeviceInfoModel;
use Virgil\Sdk\Client\Card\Model\RevokeCardContentModel;
use Virgil\Sdk\Client\Card\Model\SignedRequestMetaModel;
use Virgil\Sdk\Client\Card\Model\SignedRequestModel;
use Virgil\Sdk\Client\Constants\CardScope;

class SignedRequestModelMapperTest extends TestCase
{
    /**
     * @dataProvider createCardDataProvider
     *
     * @param $expectedJson
     * @param $contentData
     * @param $metaData
     */
    public function testMapSignedCreateRequestModelToJson ($expectedJson, $contentData, $metaData)
    {
        $mapper = new SignedRequestModelMapper();
        $model = new SignedRequestModel(new CardContentModel(...$contentData), new SignedRequestMetaModel(...$metaData));

        $this->assertEquals($expectedJson, $mapper->toJson($model));
    }


    /**
     * @dataProvider revokeCardDataProvider
     *
     * @param $expectedJson
     * @param $contentData
     * @param $metaData
     */
    public function testMapSignedRevokeRequestModelToJson ($expectedJson, $contentData, $metaData)
    {
        $mapper = new SignedRequestModelMapper();
        $model = new SignedRequestModel(new RevokeCardContentModel(...$contentData), new SignedRequestMetaModel(...$metaData));

        $this->assertEquals($expectedJson, $mapper->toJson($model));
    }


    /**
     * @dataProvider createCardDataProvider
     *
     * @param $json
     * @param $contentData
     * @param $metaData
     */
    public function testMapSignedCreateRequestJsonToModel ($json, $contentData, $metaData)
    {
        $mapper = new CreateRequestModelMapper(new SignedRequestModelMapper());

        $expectedModel = new SignedRequestModel(new CardContentModel(...$contentData), new SignedRequestMetaModel(...$metaData));

        $model = $mapper->toModel($json);

        $this->assertEquals($expectedModel, $model);
    }


    /**
     * @dataProvider revokeCardDataProvider
     *
     * @param $json
     * @param $contentData
     * @param $metaData
     */
    public function testMapSignedRevokeRequestJsonToModel ($json, $contentData, $metaData)
    {
        $mapper = new RevokeRequestModelMapper(new SignedRequestModelMapper());

        $expectedModel = new SignedRequestModel(new RevokeCardContentModel(...$contentData), new SignedRequestMetaModel(...$metaData));

        $model = $mapper->toModel($json);

        $this->assertEquals($expectedModel, $model);
    }


    public function createCardDataProvider ()
    {
        return [
            [
                '{"content_snapshot":"eyJpZGVudGl0eSI6ImFsaWNlIiwiaWRlbnRpdHlfdHlwZSI6Im1lbWJlciIsInB1YmxpY19rZXkiOiJwdWJsaWMta2V5Iiwic2NvcGUiOiJhcHBsaWNhdGlvbiJ9","meta":{"signs":{"sign-id-1":"_sign1","sign-id-2":"_sign2"}}}',
                ['alice', 'member', 'public-key', CardScope::TYPE_APPLICATION],
                [['sign-id-1' => '_sign1', 'sign-id-2' => '_sign2']],
            ],
            [
                '{"content_snapshot":"eyJpZGVudGl0eSI6ImFsaWNlMiIsImlkZW50aXR5X3R5cGUiOiJtZW1iZXIiLCJwdWJsaWNfa2V5IjoicHVibGljLWtleS0yIiwiZGF0YSI6eyJjdXN0b21EYXRhIjoicXdlcnR5In0sInNjb3BlIjoiZ2xvYmFsIiwiaW5mbyI6eyJkZXZpY2UiOiJpUGhvbmU2cyIsImRldmljZV9uYW1lIjoiU3BhY2UgZ3JleSBvbmUifX0=","meta":{"signs":{"sign-id-3":"_sign3","sign-id-4":"_sign4"}}}',
                [
                    'alice2',
                    'member',
                    'public-key-2',
                    CardScope::TYPE_GLOBAL,
                    ['customData' => 'qwerty'],
                    new DeviceInfoModel('iPhone6s', 'Space grey one'),
                ],
                [['sign-id-3' => '_sign3', 'sign-id-4' => '_sign4']],
            ],
        ];
    }


    public function revokeCardDataProvider ()
    {
        return [
            [
                '{"content_snapshot":"eyJjYXJkX2lkIjoiYWxpY2UtZmluZ2VycHJpbnQtaWQtMSIsInJldm9jYXRpb25fcmVhc29uIjoiY29tcHJvbWlzZWQifQ==","meta":{"signs":{"sign-id-1":"_sign1","sign-id-2":"_sign2"}}}',
                ['alice-fingerprint-id-1', 'compromised'],
                [['sign-id-1' => '_sign1', 'sign-id-2' => '_sign2']],
            ],
            [
                '{"content_snapshot":"eyJjYXJkX2lkIjoiYWxpY2UtZmluZ2VycHJpbnQtaWQtMiIsInJldm9jYXRpb25fcmVhc29uIjoidW5zcGVjaWZpZWQifQ==","meta":{"signs":{"sign-id-3":"_sign3","sign-id-4":"_sign4"}}}',
                ['alice-fingerprint-id-2', 'unspecified'],
                [['sign-id-3' => '_sign3', 'sign-id-4' => '_sign4']],
            ],
        ];
    }
}
