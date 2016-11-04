<?php

namespace Virgil\SDK\Client\Card\Mapper;


use Virgil\SDK\Client\Card\Model\SignedResponseModel;
use Virgil\SDK\Client\JsonModelMapper;

class SearchCriteriaResponseMapper implements JsonModelMapper
{

    /**
     * @var SignedResponseModelMapper
     */
    private $mapper;

    public function __construct(SignedResponseModelMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    /**
     * @inheritdoc
     * @return SignedResponseModel[]
     */
    public function toModel($json)
    {
        $models = [];
        $data = json_decode($json, true);
        foreach ($data as $item) {
            $models[] = $this->mapper->toModel(json_encode($item));
        }

        return $models;
    }

    public function toJson($model)
    {
        $this->mapper->toJson($model);
    }
}