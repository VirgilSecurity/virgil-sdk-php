<?php

namespace Virgil\Tests\Unit\Client;


use PHPUnit\Framework\TestCase;
use Virgil\SDK\Client\CardScope;
use Virgil\SDK\Client\Model\CreateCardContentModel;
use Virgil\SDK\Client\Model\DeviceInfoModel;
use Virgil\SDK\Client\Model\RevokeCardContentModel;
use Virgil\SDK\Client\Model\SignedRequestMetaModel;
use Virgil\SDK\Client\Model\SignedRequestModel;
use Virgil\SDK\Client\SearchCriteria;

class CardModelJsonSerializationTest extends TestCase
{
    /**
     * @dataProvider createCardDataProvider
     * @param $expectedJson
     * @param $contentData
     * @param $metaData
     */
    public function testCreateCardModel($expectedJson, $contentData, $metaData)
    {
        $model = new SignedRequestModel(
            new CreateCardContentModel(...$contentData),
            new SignedRequestMetaModel(...$metaData)
        );

        $this->assertEquals($expectedJson, $model->__toString());
    }

    /**
     * @dataProvider revokeCardDataProvider
     * @param $expectedJson
     * @param $contentData
     * @param $metaData
     */
    public function testRevokeCardModel($expectedJson, $contentData, $metaData)
    {
        $model = new SignedRequestModel(
            new RevokeCardContentModel(...$contentData),
            new SignedRequestMetaModel(...$metaData)
        );

        $this->assertEquals($expectedJson, $model->__toString());
    }

    /**
     * @dataProvider searchCardDataProvider
     * @param $expectedJson
     * @param $args
     */
    public function testSearchCriteria($expectedJson, $args)
    {
        $model = new SearchCriteria(...$args);
        $this->assertEquals($expectedJson, $model->__toString());
    }

    public function searchCardDataProvider()
    {
        return [
            [   '{"identities":["user@virgilsecurity.com","another.user@virgilsecurity.com"],"identity_type":"email","scope":"global"}',
                [['user@virgilsecurity.com', 'another.user@virgilsecurity.com'], 'email', CardScope::TYPE_GLOBAL]
            ],
            [   '{"identities":["user2@virgilsecurity.com","another.user2@virgilsecurity.com"]}',
                [['user2@virgilsecurity.com', 'another.user2@virgilsecurity.com']]
            ]
        ];
    }

    public function createCardDataProvider()
    {
        return [
            [
                '{"content_snapshot":"eyJpZGVudGl0eSI6ImFsaWNlIiwiaWRlbnRpdHlfdHlwZSI6Im1lbWJlciIsInB1YmxpY19rZXkiOiJwdWJsaWMta2V5Iiwic2NvcGUiOiJhcHBsaWNhdGlvbiJ9","meta":{"signs":{"sign-id-1":"_sign1","sign-id-2":"_sign2"}}}',
                ['alice', 'member', 'public-key', CardScope::TYPE_APPLICATION],
                [['sign-id-1' => '_sign1', 'sign-id-2' => '_sign2']]
            ],
            [
                '{"content_snapshot":"eyJpZGVudGl0eSI6ImFsaWNlMiIsImlkZW50aXR5X3R5cGUiOiJtZW1iZXIiLCJwdWJsaWNfa2V5IjoicHVibGljLWtleS0yIiwiZGF0YSI6eyJjdXN0b21EYXRhIjoicXdlcnR5In0sInNjb3BlIjoiZ2xvYmFsIiwiaW5mbyI6eyJkZXZpY2UiOiJpUGhvbmU2cyIsImRldmljZV9uYW1lIjoiU3BhY2UgZ3JleSBvbmUifX0=","meta":{"signs":{"sign-id-3":"_sign3","sign-id-4":"_sign4"}}}',
                ['alice2', 'member', 'public-key-2', CardScope::TYPE_GLOBAL, ['customData' => 'qwerty'], new DeviceInfoModel('iPhone6s', 'Space grey one')],
                [['sign-id-3' => '_sign3', 'sign-id-4' => '_sign4']]
            ]
        ];
    }

    public function revokeCardDataProvider()
    {
        return [
            [
                '{"content_snapshot":"eyJpZCI6ImFsaWNlLWZpbmdlcnByaW50LWlkLTEiLCJyZXZvY2F0aW9uX3JlYXNvbiI6ImNvbXByb21pc2VkIn0=","meta":{"signs":{"sign-id-1":"_sign1","sign-id-2":"_sign2"}}}',
                ['alice-fingerprint-id-1', 'compromised'],
                [['sign-id-1' => '_sign1', 'sign-id-2' => '_sign2']]
            ],
            [
                '{"content_snapshot":"eyJpZCI6ImFsaWNlLWZpbmdlcnByaW50LWlkLTIiLCJyZXZvY2F0aW9uX3JlYXNvbiI6InVuc3BlY2lmaWVkIn0=","meta":{"signs":{"sign-id-3":"_sign3","sign-id-4":"_sign4"}}}',
                ['alice-fingerprint-id-2', 'unspecified'],
                [['sign-id-3' => '_sign3', 'sign-id-4' => '_sign4']]
            ]
        ];
    }
}