<?php
namespace Virgil\Sdk\Tests\Unit\Client\VirgilCards\Mapper;


use RuntimeException;

use Virgil\Sdk\Client\VirgilCards\Mapper\SignedRequestModelMapper;

use Virgil\Sdk\Client\VirgilCards\Model\DeviceInfoModel;
use Virgil\Sdk\Client\VirgilCards\Model\SignedRequestMetaModel;
use Virgil\Sdk\Client\VirgilCards\Model\SignedRequestModel;

class SignedRequestModelMapperTest extends AbstractMapperTest
{

    /**
     * @dataProvider signedRequestDataProvider
     *
     * @param array $signedRequestJsonData
     * @param array $signedRequestData
     *
     * @test
     *
     */
    public function toJson__fromSignedRequestModel__returnsValidSignedRequestJsonString(
        array $signedRequestJsonData,
        array $signedRequestData
    ) {
        $expectedSignedRequestJson = $this->createSignedCardRequestJson(...$signedRequestJsonData);

        $signedRequestModel = $this->createSignedRequestModel(...$signedRequestData);


        $signedRequestJson = $this->mapper->toJson($signedRequestModel);


        $this->assertEquals($expectedSignedRequestJson, $signedRequestJson);
    }


    /**
     * @expectedException RuntimeException
     *
     * @test
     *
     */
    public function toModel__fromSignedRequestModelJsonString__throwsException()
    {
        $signedRequestJson = '{"content_snapshot":"eyJkZXZpY2UiOiJzYW0iLCJkZXZpY2VfbmFtZSI6Im5vdGUifQ==","meta":{"signs":{"sign-id-3":"_sign3"}}}';


        $this->mapper->toModel($signedRequestJson);


        //expected exception
    }


    public function signedRequestDataProvider()
    {
        return [
            [
                [self::CARD_SIGNED_REQUEST_JSON_FORMAT, '[]', '{"signs":{"sign-id-1":"_sign1","sign-id-2":"_sign2"}}'],
                [
                    [],
                    ['sign-id-1' => '_sign1', 'sign-id-2' => '_sign2'],
                ],
            ],
            [
                [
                    self::CARD_SIGNED_REQUEST_JSON_FORMAT,
                    '{"device":"sam","device_name":"note"}',
                    '{"signs":{"sign-id-3":"_sign3"}}',
                ],
                [
                    ["sam", "note"],
                    ['sign-id-3' => '_sign3'],
                ],
            ],
        ];
    }


    protected function getMapper()
    {
        return $this->createSignedRequestModelMapper();
    }


    private function createSignedRequestModelMapper()
    {
        return new SignedRequestModelMapper();
    }


    private function createSignedRequestModel($cardContentData, $cardSigns)
    {
        return new SignedRequestModel(
            new DeviceInfoModel(...$cardContentData), new SignedRequestMetaModel($cardSigns)
        );
    }
}
