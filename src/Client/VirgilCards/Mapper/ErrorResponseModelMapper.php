<?php
namespace Virgil\Sdk\Client\VirgilCards\Mapper;


use Virgil\Sdk\Client\AbstractJsonModelMapper;

use Virgil\Sdk\Client\VirgilCards\Model\ErrorResponseModel;

/**
 * Class transforms error response message string to model.
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

        return new ErrorResponseModel($data['code']);
    }
}
