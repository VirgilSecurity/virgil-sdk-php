<?php
namespace Virgil\Sdk\Client\VirgilServices\Mapper;


/**
 * Interface provides methods for model converting to json and vise versa.
 */
interface JsonModelMapperInterface
{
    /**
     * Make model from json string.
     *
     * @param $json
     *
     * @return mixed
     */
    public function toModel($json);


    /**
     * Make json string from model.
     *
     * @param mixed $model
     *
     * @return string
     */
    public function toJson($model);
}
