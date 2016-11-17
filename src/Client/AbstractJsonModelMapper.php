<?php

namespace Virgil\SDK\Client;


abstract class AbstractJsonModelMapper implements JsonModelMapperInterface
{
    public function toModel($json)
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' is disabled for this mapper');
    }

    public function toJson($model)
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' is disabled for this mapper');
    }
}