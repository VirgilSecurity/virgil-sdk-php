<?php
namespace Virgil\Sdk\Tests\Unit\Client\Card;


use DateTime;

use Virgil\Sdk\Buffer;

use Virgil\Sdk\Contracts\BufferInterface;

use Virgil\Sdk\Client\Card\PublishRequestCardMapper;

use Virgil\Sdk\Client\Requests\Constants\CardScopes;

use Virgil\Sdk\Client\VirgilServices\Model\DeviceInfoModel;

use Virgil\Sdk\Tests\BaseTestCase;

use Virgil\Sdk\Tests\Unit\Card;

use Virgil\Sdk\Tests\Unit\Client\VirgilServices\Model\RequestModel;

class PublishRequestCardMapperTest extends BaseTestCase
{
    /**
     * @dataProvider publishRequestCardDataProvider
     *
     * @test
     *
     * @param                 $cardId
     * @param BufferInterface $contentSnapshot
     * @param                 $validationToken
     * @param                 $identity
     * @param                 $identityType
     * @param BufferInterface $identityPublicKey
     * @param                 $cardData
     * @param                 $device
     * @param                 $deviceName
     * @param                 $version
     * @param BufferInterface $signature
     * @param                 $signatureKey
     * @param                 $createdAt
     */
    public function toModel__fromCard__returnsSignedRequestModel(
        $cardId,
        $contentSnapshot,
        $validationToken,
        $identity,
        $identityType,
        $identityPublicKey,
        $cardData,
        $device,
        $deviceName,
        $version,
        $signature,
        $signatureKey,
        $createdAt
    ) {
        $cardScope = CardScopes::TYPE_APPLICATION;

        $card = Card::createCard(
            [
                $cardId,
                $contentSnapshot,
                $identity,
                $identityType,
                $identityPublicKey,
                $cardScope,
                $cardData,
                $device,
                $deviceName,
                $version,
                [$signatureKey => $signature],
                $createdAt,
            ]
        );

        $expectedSignedRequestModel = RequestModel::createCreateCardRequestModel(
            [
                $identity,
                $identityType,
                $identityPublicKey->toBase64(),
                $cardScope,
                $cardData,
                new DeviceInfoModel($device, $deviceName),
            ],
            [
                [$signatureKey => $signature->toBase64()],
            ],
            $contentSnapshot->getData()
        );

        $publishRequestCardMapper = $this->createPublishRequestCardMapper();


        $signedRequestModel = $publishRequestCardMapper->toModel($card);


        $this->assertEquals($expectedSignedRequestModel, $signedRequestModel);
    }


    /**
     * @dataProvider publishRequestCardDataProvider
     *
     * @test
     *
     * @param                 $cardId
     * @param BufferInterface $contentSnapshot
     * @param                 $validationToken
     * @param                 $identity
     * @param                 $identityType
     * @param BufferInterface $identityPublicKey
     * @param                 $cardData
     * @param                 $device
     * @param                 $deviceName
     * @param                 $version
     * @param BufferInterface $signature
     * @param                 $signatureKey
     * @param                 $createdAt
     */
    public function toModel__fromGlobalCard__returnsSignedRequestModelWithValidationToken(
        $cardId,
        $contentSnapshot,
        $validationToken,
        $identity,
        $identityType,
        $identityPublicKey,
        $cardData,
        $device,
        $deviceName,
        $version,
        $signature,
        $signatureKey,
        $createdAt
    ) {
        $cardScope = CardScopes::TYPE_GLOBAL;

        $card = Card::createCard(
            [
                $cardId,
                $contentSnapshot,
                $identity,
                $identityType,
                $identityPublicKey,
                $cardScope,
                $cardData,
                $device,
                $deviceName,
                $version,
                [$signatureKey => $signature],
                $createdAt,
            ]
        );

        $expectedSignedRequestModel = RequestModel::createCreateCardRequestModel(
            [
                $identity,
                $identityType,
                $identityPublicKey->toBase64(),
                $cardScope,
                $cardData,
                new DeviceInfoModel($device, $deviceName),
            ],
            [
                [$signatureKey => $signature->toBase64()],
                $validationToken,
            ],
            $contentSnapshot->getData()
        );

        $publishRequestCardMapper = $this->createPublishRequestCardMapper();


        $signedRequestModel = $publishRequestCardMapper->toModel($card, $validationToken);


        $this->assertEquals($expectedSignedRequestModel, $signedRequestModel);
    }


    public function publishRequestCardDataProvider()
    {
        return [
            [
                'card-id',
                new Buffer('f27o8fh2'),
                '3u9g0349yuob',
                'alice',
                'member',
                new Buffer('alice-public-key'),
                ['key' => 'value'],
                'smsng',
                'glx',
                'v12',
                new Buffer('sign-1'),
                'sign-1',
                new DateTime('2016-11-04T13:16:17+0000'),
            ],
        ];
    }


    protected function createPublishRequestCardMapper()
    {
        return new PublishRequestCardMapper();
    }
}
