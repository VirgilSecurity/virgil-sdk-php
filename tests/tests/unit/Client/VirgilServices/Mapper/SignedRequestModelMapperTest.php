<?php
namespace Virgil\Sdk\Tests\Unit\Client\VirgilServices\Mapper;


use Virgil\Sdk\Client\VirgilServices\Mapper\SignedRequestModelMapper;

use Virgil\Sdk\Tests\Unit\Client\VirgilServices\VirgilCards\Model\RequestModel;

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

        $signedRequestModel = RequestModel::createDeviceInfoContentRequestModel(...$signedRequestData);


        $signedRequestJson = $this->mapper->toJson($signedRequestModel);


        $this->assertEquals($expectedSignedRequestJson, $signedRequestJson);
    }


    /**
     * @expectedException \Virgil\Sdk\Exceptions\MethodIsDisabledException
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
}
