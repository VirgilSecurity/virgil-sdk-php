<?php
namespace Virgil\Sdk\Tests\Unit\Client\VirgilServices\VirgilCards\Mapper;


use Virgil\Sdk\Client\Requests\Constants\CardScopes;

use Virgil\Sdk\Client\VirgilServices\VirgilCards\Mapper\CreateRequestModelMapper;
use Virgil\Sdk\Client\VirgilServices\VirgilCards\Mapper\CardContentModelMapper;

use Virgil\Sdk\Client\VirgilServices\VirgilCards\Model\DeviceInfoModel;

use Virgil\Sdk\Tests\Unit\Client\VirgilServices\VirgilCards\Model\RequestModel;

class CreateRequestModelMapperTest extends AbstractVirgilCardsMapperTest
{
    /**
     * @dataProvider createCardDataProvider
     *
     * @param array $createCardRequestJsonData
     * @param array $createCardRequestData
     *
     * @test
     */
    public function toJson__fromCreateCardRequestModel__returnsValidCreateCardRequestJsonString(
        array $createCardRequestJsonData,
        array $createCardRequestData
    ) {
        $expectedCreateCardRequestJson = $this->createCreateCardRequestJson(...$createCardRequestJsonData);

        $createCardRequestModel = RequestModel::createCreateCardRequestModel(...$createCardRequestData);


        $createCardRequestJson = $this->mapper->toJson($createCardRequestModel);


        $this->assertEquals($expectedCreateCardRequestJson, $createCardRequestJson);
    }


    /**
     * @dataProvider createCardDataProvider
     *
     * @param array $createCardRequestJsonData
     * @param array $createCardRequestData
     *
     * @test
     */
    public function toModel__fromCreateCardRequestModelJsonString__returnsValidCreateCardRequestModel(
        array $createCardRequestJsonData,
        array $createCardRequestData
    ) {
        $createCardRequestModelJson = $this->createCreateCardRequestJson(...$createCardRequestJsonData);

        $expectedCreateCardRequestModel = RequestModel::createCreateCardRequestModel(...$createCardRequestData);


        $createCardRequestModel = $this->mapper->toModel($createCardRequestModelJson);


        $this->assertEquals($expectedCreateCardRequestModel, $createCardRequestModel);
    }


    public function createCardDataProvider()
    {
        return [
            [
                [
                    self::CARD_SIGNED_REQUEST_JSON_FORMAT,
                    '{"identity":"alice","identity_type":"member","public_key":"public-key","scope":"application"}',
                    '{"signs":{"sign-id-1":"_sign1","sign-id-2":"_sign2"}}',
                ],
                [
                    ['alice', 'member', 'public-key', CardScopes::TYPE_APPLICATION],
                    ['sign-id-1' => '_sign1', 'sign-id-2' => '_sign2'],
                ],
            ],
            [
                [
                    self::CARD_SIGNED_REQUEST_JSON_FORMAT,
                    '{"identity":"alice2","identity_type":"member","public_key":"public-key-2","data":{"customData":"qwerty"},"scope":"global","info":{"device":"iPhone6s","device_name":"Space grey one"}}',
                    '{"signs":{"sign-id-3":"_sign3","sign-id-4":"_sign4"}}',
                ],
                [
                    [
                        'alice2',
                        'member',
                        'public-key-2',
                        CardScopes::TYPE_GLOBAL,
                        ['customData' => 'qwerty'],
                        new DeviceInfoModel('iPhone6s', 'Space grey one'),
                    ],
                    ['sign-id-3' => '_sign3', 'sign-id-4' => '_sign4'],
                ],
            ],
        ];
    }


    protected function getMapper()
    {
        return $this->createCreateCardRequestModelMapper();
    }


    private function createCreateCardRequestModelMapper()
    {
        return new CreateRequestModelMapper(
            new CardContentModelMapper()
        );
    }


    private function createCreateCardRequestJson($format, $cardContentJson, $cardMetaJson)
    {
        return $this->createSignedCardRequestJson($format, $cardContentJson, $cardMetaJson);
    }
}
