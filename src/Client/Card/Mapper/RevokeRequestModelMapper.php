<?php

namespace Virgil\SDK\Client\Card\Mapper;


use Virgil\SDK\Client\AbstractJsonModelMapper;
use Virgil\SDK\Client\Card\Model\RevokeCardContentModel;
use Virgil\SDK\Client\Card\Model\SignedRequestMetaModel;
use Virgil\SDK\Client\Card\Model\SignedRequestModel;

class RevokeRequestModelMapper extends AbstractJsonModelMapper
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

        $cardContentModel = new RevokeCardContentModel(
            $cardContentData['card_id'],
            $cardContentData['revocation_reason']
        );

        $cardMetaModel = new SignedRequestMetaModel($cardMetaData['signs']);

        return new SignedRequestModel($cardContentModel, $cardMetaModel);
    }

    public function toJson($model)
    {
        return $this->signedRequestModelMapper->toJson($model);
    }
}