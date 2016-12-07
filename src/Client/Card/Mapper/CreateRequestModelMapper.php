<?php
namespace Virgil\Sdk\Client\Card\Mapper;


use Virgil\Sdk\Client\AbstractJsonModelMapper;
use Virgil\Sdk\Client\Card\Model\CardContentModel;
use Virgil\Sdk\Client\Card\Model\DeviceInfoModel;
use Virgil\Sdk\Client\Card\Model\SignedRequestMetaModel;
use Virgil\Sdk\Client\Card\Model\SignedRequestModel;

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
