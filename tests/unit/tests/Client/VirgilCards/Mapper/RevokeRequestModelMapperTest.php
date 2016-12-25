<?php
namespace Virgil\Sdk\Tests\Unit\Client\VirgilCards\Mapper;


use Virgil\Sdk\Client\VirgilCards\Mapper\RevokeRequestModelMapper;
use Virgil\Sdk\Client\VirgilCards\Mapper\SignedRequestModelMapper;

use Virgil\Sdk\Client\VirgilCards\Model\RevokeCardContentModel;
use Virgil\Sdk\Client\VirgilCards\Model\SignedRequestMetaModel;
use Virgil\Sdk\Client\VirgilCards\Model\SignedRequestModel;

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

        $revokeCardRequestModel = $this->createRevokeCardRequestModel(...$revokeCardRequestData);


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

        $expectedRevokeCardRequestModel = $this->createRevokeCardRequestModel(...$revokeCardRequestData);


        $revokeCardRequestModel = $this->mapper->toModel($revokeCardRequestModelJson);


        $this->assertEquals($expectedRevokeCardRequestModel, $revokeCardRequestModel);
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
            ],
        ];
    }


    protected function getMapper()
    {
        return $this->createRevokeRequestModelMapper();
    }


    private function createRevokeRequestModelMapper()
    {
        return new RevokeRequestModelMapper(new SignedRequestModelMapper());
    }


    private function createRevokeCardRequestModel($contentData, $cardSigns)
    {
        return new SignedRequestModel(
            new RevokeCardContentModel(...$contentData), new SignedRequestMetaModel($cardSigns)
        );
    }

    private function createRevokeCardRequestJson($format, $cardContentJson, $cardMetaJson)
    {
        return $this->createSignedCardRequestJson($format, $cardContentJson, $cardMetaJson);
    }
}
