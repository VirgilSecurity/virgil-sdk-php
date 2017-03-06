<?php
namespace Virgil\Sdk\Tests\Unit\Client\Card;


use DateTime;
use Virgil\Sdk\Buffer;

use Virgil\Sdk\Client\Card\SignedResponseCardMapper;

use Virgil\Sdk\Client\Requests\Constants\CardScopes;
use Virgil\Sdk\Client\VirgilServices\Model\DeviceInfoModel;
use Virgil\Sdk\Tests\BaseTestCase;
use Virgil\Sdk\Tests\Unit\Card;
use Virgil\Sdk\Tests\Unit\Client\VirgilServices\Model\ResponseModel;

class SignedResponseCardMapperTest extends BaseTestCase
{
    /**
     * @dataProvider signedResponseCardDataProvider
     *
     * @param                 $id
     * @param                 $contentSnapshot
     * @param                 $identity
     * @param                 $identityType
     * @param                 $publicKey
     * @param                 $scope
     * @param                 $data
     * @param DeviceInfoModel $deviceInfo
     * @param                 $signs
     * @param                 $createdAt
     * @param                 $version
     *
     * @test
     */
    public function toCard__fromSignedResponseModel__returnsCard(
        $id,
        $contentSnapshot,
        $identity,
        $identityType,
        $publicKey,
        $scope,
        $data,
        DeviceInfoModel $deviceInfo,
        $signs,
        $createdAt,
        $version
    ) {
        $signedResponseModel = ResponseModel::createSignedResponseModel(
            $id,
            $contentSnapshot,
            [
                $identity,
                $identityType,
                $publicKey,
                $scope,
                $data,
                $deviceInfo,
            ],
            [
                $signs,
                $createdAt,
                $version,
            ]
        );

        $cardSigns = array_map(
            function ($sign) {
                return Buffer::fromBase64($sign);
            },
            $signs
        );

        $signedResponseCardMapper = new SignedResponseCardMapper();

        $expectedCard = Card::createCard(
            [
                $id,
                Buffer::fromBase64($contentSnapshot),
                $identity,
                $identityType,
                Buffer::fromBase64($publicKey),
                $scope,
                $data,
                $deviceInfo->getDevice(),
                $deviceInfo->getDeviceName(),
                $version,
                $cardSigns,
                $createdAt,
            ]
        );


        $card = $signedResponseCardMapper->toCard($signedResponseModel);


        $this->assertEquals($expectedCard, $card);
    }


    /**
     * @dataProvider signedResponseCardDataProvider
     *
     * @param                 $id
     * @param                 $contentSnapshot
     * @param                 $identity
     * @param                 $identityType
     * @param                 $publicKey
     * @param                 $scope
     * @param                 $data
     * @param DeviceInfoModel $deviceInfo
     * @param                 $signs
     * @param                 $createdAt
     * @param                 $version
     *
     * @test
     */
    public function toModel__fromCards__returnsSignedResponseModel(
        $id,
        $contentSnapshot,
        $identity,
        $identityType,
        $publicKey,
        $scope,
        $data,
        DeviceInfoModel $deviceInfo,
        $signs,
        $createdAt,
        $version
    ) {
        $expectedSignedResponseModel = ResponseModel::createSignedResponseModel(
            $id,
            $contentSnapshot,
            [
                $identity,
                $identityType,
                $publicKey,
                $scope,
                $data,
                $deviceInfo,
            ],
            [
                $signs,
                $createdAt,
                $version,
            ]
        );


        $cardSigns = array_map(
            function ($sign) {
                return Buffer::fromBase64($sign);
            },
            $signs
        );

        $card = Card::createCard(
            [
                $id,
                Buffer::fromBase64($contentSnapshot),
                $identity,
                $identityType,
                Buffer::fromBase64($publicKey),
                $scope,
                $data,
                $deviceInfo->getDevice(),
                $deviceInfo->getDeviceName(),
                $version,
                $cardSigns,
                $createdAt,
            ]
        );

        $signedResponseCardMapper = new SignedResponseCardMapper();


        $signedResponseModel = $signedResponseCardMapper->toModel($card);


        $this->assertEquals($expectedSignedResponseModel, $signedResponseModel);
    }


    public function signedResponseCardDataProvider()
    {
        return [
            [
                'model-id-1',
                'eyJpZGVudGl0eSI6ImFsaWNlMiIsImlkZW50aXR5X3R5cGUiOiJtZW1iZXIiLCJwdWJsaWNfa2V5IjoiY0hWaWJHbGpMV3RsZVMweSIsImRhdGEiOnsiY3VzdG9tRGF0YSI6InF3ZXJ0eSJ9LCJzY29wZSI6Imdsb2JhbCIsImluZm8iOnsiZGV2aWNlIjoiaVBob25lNnMiLCJkZXZpY2VfbmFtZSI6IlNwYWNlIGdyZXkgb25lIn19',
                'alice2',
                'member',
                'cHVibGljLWtleS0y',
                CardScopes::TYPE_GLOBAL,
                ['customData' => 'qwerty'],
                new DeviceInfoModel('iPhone6s', 'Space grey one'),
                ['sign-id-3' => 'X3NpZ24z'],
                new DateTime('2016-11-04T13:16:17+0000'),
                'v4',
            ],
        ];
    }
}
