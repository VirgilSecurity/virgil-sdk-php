<?php
namespace Virgil\Sdk\Tests\Unit\Client\VirgilServices\VirgilCards\Mapper;


use Virgil\Sdk\Client\VirgilServices\VirgilCards\Mapper\RevokeRequestModelMapper;

use Virgil\Sdk\Tests\Unit\Client\VirgilServices\Mapper\AbstractMapperTest;
use Virgil\Sdk\Tests\Unit\Client\VirgilServices\VirgilCards\Model\RequestModel;

class RevokeRequestModelMapperTest extends AbstractMapperTest
{
    /**
     * @dataProvider revokeCardDataProvider
     *
     * @param array $revokeCardRequestJsonData
     * @param array $revokeCardRequestData
     *
     * @test
     */
    public function toJson__fromRevokeCardRequestModel__returnsValidRevokeCardRequestJsonString(
        array $revokeCardRequestJsonData,
        array $revokeCardRequestData
    ) {
        $expectedRevokeCardRequestJson = $this->createRevokeCardRequestJson(...$revokeCardRequestJsonData);

        $revokeCardRequestModel = RequestModel::createRevokeCardRequestModel(...$revokeCardRequestData);


        $revokeCardRequestJson = $this->mapper->toJson($revokeCardRequestModel);


        $this->assertEquals($expectedRevokeCardRequestJson, $revokeCardRequestJson);
    }


    /**
     * @dataProvider revokeCardDataProvider
     *
     * @param array $revokeCardRequestJsonData
     * @param array $revokeCardRequestData
     *
     * @test
     */
    public function toModel__fromRevokeCardRequestModelJsonString__returnsValidRevokeCardRequestModel(
        array $revokeCardRequestJsonData,
        array $revokeCardRequestData
    ) {
        $revokeCardRequestModelJson = $this->createRevokeCardRequestJson(...$revokeCardRequestJsonData);

        $expectedRevokeCardRequestModel = RequestModel::createRevokeCardRequestModel(...$revokeCardRequestData);


        $revokeCardRequestModel = $this->mapper->toModel($revokeCardRequestModelJson);


        $this->assertEquals($expectedRevokeCardRequestModel, $revokeCardRequestModel);
    }


    /**
     * @dataProvider revokeCardDataProvider
     *
     * @param array $revokeCardRequestJsonData
     * @param array $revokeCardRequestData
     * @param       $exportedContentSnapshot
     *
     * @test
     */
    public function toModel__fromExportedRevokeCardRequestModel__keepsExportedContentSnapshot(
        array $revokeCardRequestJsonData,
        array $revokeCardRequestData,
        $exportedContentSnapshot
    ) {
        $revokeCardRequestModelJson = $this->createRevokeCardRequestJson(...$revokeCardRequestJsonData);

        list($revokeCardRequestContentData, $revokeCardRequestSignsData) = $revokeCardRequestData;

        $expectedRevokeCardRequestModel = RequestModel::createRevokeCardRequestModel(
            $revokeCardRequestContentData,
            $revokeCardRequestSignsData,
            $exportedContentSnapshot
        );


        $revokeCardRequestModel = $this->mapper->toModel($revokeCardRequestModelJson);


        $this->assertEquals(
            $expectedRevokeCardRequestModel->getRequestContent(),
            $revokeCardRequestModel->getRequestContent()
        );

        $this->assertEquals(
            $expectedRevokeCardRequestModel->getRequestMeta(),
            $revokeCardRequestModel->getRequestMeta()
        );

        $this->assertNotEquals($expectedRevokeCardRequestModel->getSnapshot(), $revokeCardRequestModel->getSnapshot());
    }


    public function revokeCardDataProvider()
    {
        return [
            [
                [
                    self::CARD_SIGNED_REQUEST_JSON_FORMAT,
                    '{"card_id":"alice-fingerprint-id-1","revocation_reason":"compromised"}',
                    '{"signs":{"sign-id-1":"_sign1","sign-id-2":"_sign2"}}',
                ],
                [
                    ['alice-fingerprint-id-1', 'compromised'],
                    ['sign-id-1' => '_sign1', 'sign-id-2' => '_sign2'],
                ],
                'eyJyZXZvY2F0aW9uX3JlYXNvbiI6ImNvbXByb21pc2VkIiwiY2FyZF9pZCI6ImFsaWNlLWZpbmdlcnByaW50LWlkLTEifQ==',
            ],
            [
                [
                    self::CARD_SIGNED_REQUEST_JSON_FORMAT,
                    '{"card_id":"alice-fingerprint-id-2","revocation_reason":"unspecified"}',
                    '{"signs":{"sign-id-3":"_sign3","sign-id-4":"_sign4"}}',
                ],
                [
                    ['alice-fingerprint-id-2', 'unspecified'],
                    ['sign-id-3' => '_sign3', 'sign-id-4' => '_sign4'],
                ],
                'eyJyZXZvY2F0aW9uX3JlYXNvbiI6InVuc3BlY2lmaWVkIiwiY2FyZF9pZCI6ImFsaWNlLWZpbmdlcnByaW50LWlkLTIifQ==',
            ],
        ];
    }


    protected function getMapper()
    {
        return $this->createRevokeRequestModelMapper();
    }


    private function createRevokeRequestModelMapper()
    {
        return new RevokeRequestModelMapper();
    }


    private function createRevokeCardRequestJson($format, $cardContentJson, $cardMetaJson)
    {
        return $this->createSignedCardRequestJson($format, $cardContentJson, $cardMetaJson);
    }
}
