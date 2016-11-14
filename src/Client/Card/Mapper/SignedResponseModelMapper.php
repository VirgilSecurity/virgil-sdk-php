<?php

namespace Virgil\SDK\Client\Card\Mapper;


use Virgil\SDK\Client\Card\Model\CardContentModel;
use Virgil\SDK\Client\Card\Model\DeviceInfoModel;
use Virgil\SDK\Client\Card\Model\SignedResponseMetaModel;
use Virgil\SDK\Client\Card\Model\SignedResponseModel;
use Virgil\SDK\Client\JsonModelMapper;

class SignedResponseModelMapper implements JsonModelMapper
{
    /**
     * @inheritdoc
     * @return SignedResponseModel
     */
    public function toModel($json)
    {
        $data = json_decode($json, true);
        $cardContentData = json_decode(base64_decode($data['content_snapshot']), true);
        $cardMetaData = $data['meta'];

        $cardContentModel = new CardContentModel(
            $cardContentData['identity'],
            $cardContentData['identity_type'],
            $cardContentData['public_key'],
            $cardContentData['scope'],
            is_array($cardContentData['data']) ? $cardContentData['data'] : [],
            new DeviceInfoModel($cardContentData['info']['device'], $cardContentData['info']['device_name'])
        );

        $cardMetaModel = new SignedResponseMetaModel(
            $cardMetaData['signs'],
            new \DateTime($cardMetaData['created_at']),
            $cardMetaData['card_version'],
            $cardMetaData['fingerprint']
        );

        return new SignedResponseModel($data['id'], $data['content_snapshot'], $cardContentModel, $cardMetaModel);
    }

    public function toJson($model)
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' is disabled for this mapper');
    }
}