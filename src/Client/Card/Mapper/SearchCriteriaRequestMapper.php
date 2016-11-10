<?php

namespace Virgil\SDK\Client\Card\Mapper;


use Virgil\SDK\Client\Card\Model\SearchCriteria;
use Virgil\SDK\Client\JsonModelMapper;

class SearchCriteriaRequestMapper implements JsonModelMapper
{

    public function toModel($json)
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' is disabled for this mapper');
    }

    public function toJson($model)
    {
        /** @var SearchCriteria $model */
        if (!$model instanceof SearchCriteria) {
            throw new \InvalidArgumentException('Invalid model passed. Instance of SearchCriteria accept only.');
        }

        return json_encode(array_filter([
            'identities' => $model->getIdentities(),
            'identity_type' => $model->getIdentityType(),
            'scope' => $model->getScope()
        ], function ($value) {
            return count($value) !== 0;
        }));
    }
}