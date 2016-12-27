<?php
namespace Virgil\Sdk\Client;


use RuntimeException;

/**
 * Base class for model mappers with disabled methods.
 */
abstract class AbstractJsonModelMapper implements JsonModelMapperInterface
{
    const CONTENT_SNAPSHOT_ATTRIBUTE_NAME = 'content_snapshot';
    const META_ATTRIBUTE_NAME = 'meta';
    const IDENTITY_ATTRIBUTE_NAME = 'identity';
    const IDENTITY_TYPE_ATTRIBUTE_NAME = 'identity_type';
    const SCOPE_ATTRIBUTE_NAME = 'scope';
    const SIGNS_ATTRIBUTE_NAME = 'signs';


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
