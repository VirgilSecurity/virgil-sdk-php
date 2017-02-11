<?php
namespace Virgil\Sdk\Client\VirgilServices\Mapper;


use Virgil\Sdk\Exceptions\MethodIsDisabledException;

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
        throw new MethodIsDisabledException(__METHOD__);
    }


    /**
     * @inheritdoc
     */
    public function toJson($model)
    {
        return json_encode($model);
    }
}
