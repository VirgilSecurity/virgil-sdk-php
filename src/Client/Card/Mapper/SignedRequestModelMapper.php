<?php

namespace Virgil\SDK\Client\Card\Mapper;


use Virgil\SDK\Client\AbstractJsonModelMapper;
use Virgil\SDK\Client\Card\Model\SignedRequestModel;

class SignedRequestModelMapper extends AbstractJsonModelMapper
{
    public function toJson($model)
    {
        /** @var SignedRequestModel $model */
        if (!$model instanceof SignedRequestModel) {
            throw new \InvalidArgumentException('Invalid model passed. Instance of SignedRequestModel accept only.');
        }

        return json_encode([
            'content_snapshot' => $model->getSnapshot(),
            'meta' => $model->getMeta()
        ]);
    }
}