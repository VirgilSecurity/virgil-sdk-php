<?php
namespace Virgil\Sdk\Client;


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
