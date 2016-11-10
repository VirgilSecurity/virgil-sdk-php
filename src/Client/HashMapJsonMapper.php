<?php

namespace Virgil\SDK\Client;


class HashMapJsonMapper implements JsonModelMapper
{

    public function toModel($json)
    {
        return json_decode($json, true);
    }

    public function toJson($model)
    {
        return json_encode($model);
    }
}