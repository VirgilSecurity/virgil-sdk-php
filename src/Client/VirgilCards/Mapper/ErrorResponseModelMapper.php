<?php
namespace Virgil\Sdk\Client\VirgilCards\Mapper;


use Virgil\Sdk\Client\VirgilCards\Constants\JsonProperties;

use Virgil\Sdk\Client\VirgilCards\Model\ErrorResponseModel;

use Virgil\Sdk\Client\VirgilServices\Mapper\AbstractJsonModelMapper;

/**
 * Class transforms error response message json string to model.
 */
class ErrorResponseModelMapper extends AbstractJsonModelMapper
{
    /**
     * @inheritdoc
     *
     * @return ErrorResponseModel
     */
    public function toModel($json)
    {
        $data = json_decode($json, true);

        return new ErrorResponseModel($data[JsonProperties::CODE_ATTRIBUTE_NAME]);
    }
}
