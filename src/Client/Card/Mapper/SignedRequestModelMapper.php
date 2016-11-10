<?php

namespace Virgil\SDK\Client\Card\Mapper;


use Virgil\SDK\Client\Card\Model\SignedRequestModel;
use Virgil\SDK\Client\JsonModelMapper;

class SignedRequestModelMapper implements JsonModelMapper
{

    public function toModel($json)
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' is disabled for this mapper');
    }

    public function toJson($model)
    {
        /** @var SignedRequestModel $model */
        if (!$model instanceof SignedRequestModel) {
            throw new \InvalidArgumentException('Invalid model passed. Instance of SignedRequestModel accept only.');
        }

        return json_encode([
            'content_snapshot' => base64_encode(json_encode($model->getCardContent())),
            'meta' => $model->getMeta()
        ]);
    }
}