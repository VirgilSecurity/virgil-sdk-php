<?php
namespace Virgil\Sdk\Client\Card\Mapper;


use Virgil\Sdk\Client\AbstractJsonModelMapper;
use Virgil\Sdk\Client\Card\Model\ErrorResponseModel;

class ErrorResponseModelMapper extends AbstractJsonModelMapper
{
    /**
     * @param $json
     *
     * @return ErrorResponseModel
     */
    public function toModel($json)
    {
        $data = json_decode($json, true);

        return new ErrorResponseModel($data['code']);
    }
}
