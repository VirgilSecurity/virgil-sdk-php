<?php
namespace Virgil\Sdk\Client\VirgilCards\Mapper;


use RuntimeException;

/**
 * Base class for model mappers with disabled methods.
 */
abstract class AbstractJsonModelMapper implements JsonModelMapperInterface
{
    /**
     * @inheritdoc
     */
    public function toModel($json)
    {
        throw new RuntimeException('Method ' . __METHOD__ . ' is disabled for this mapper');
    }


    /**
     * @inheritdoc
     */
    public function toJson($model)
    {
        throw new RuntimeException('Method ' . __METHOD__ . ' is disabled for this mapper');
    }
}
