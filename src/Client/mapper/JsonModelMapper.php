<?php

namespace Virgil\SDK\Client\Mapper;


use JsonSerializable;

interface JsonModelMapper
{
    /**
     * Make model from json string.
     *
     * @param $json
     * @return JsonSerializable
     */
    public function toModel($json);

    /**
     * Make json string from model.
     *
     * @param mixed $model
     * @return string
     */
    public function toJson($model);
}