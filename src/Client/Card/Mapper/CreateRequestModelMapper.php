<?php

namespace Virgil\SDK\Client\Card\Mapper;


use Virgil\SDK\Client\AbstractJsonModelMapper;
use Virgil\SDK\Client\Card\Model\CardContentModel;
use Virgil\SDK\Client\Card\Model\DeviceInfoModel;
use Virgil\SDK\Client\Card\Model\SignedRequestMetaModel;
use Virgil\SDK\Client\Card\Model\SignedRequestModel;

class CreateRequestModelMapper extends AbstractJsonModelMapper
{
    private $signedRequestModelMapper;

    public function __construct(SignedRequestModelMapper $signedRequestModelMapper)
    {
        $this->signedRequestModelMapper = $signedRequestModelMapper;
    }

    /**
     * @inheritdoc
     * @return SignedRequestModel
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

        $cardMetaModel = new SignedRequestMetaModel($cardMetaData['signs']);

        return new SignedRequestModel($cardContentModel, $cardMetaModel);
    }

    public function toJson($model)
    {
        return $this->signedRequestModelMapper->toJson($model);
    }
}