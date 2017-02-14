<?php
namespace Virgil\Sdk\Client\VirgilServices\Mapper;


use Virgil\Sdk\Client\VirgilServices\Constants\JsonProperties;

use Virgil\Sdk\Client\VirgilServices\Model\ErrorResponseModel;

/**
 * Base class for Virgil services error response model mappers.
 */
abstract class AbstractErrorResponseModelMapper extends AbstractJsonModelMapper
{
    /**
     * @inheritdoc
     *
     * @return ErrorResponseModel
     */
    public function toModel($json)
    {
        $data = json_decode($json, true);

        $errorCode = $data[JsonProperties::CODE_ATTRIBUTE_NAME];
        $errorMessage = '';

        if ($errorCode != '') {
            $errorMessage = $this->getErrorMessageByCode($errorCode);
        }

        return new ErrorResponseModel($errorCode, $errorMessage);
    }


    /**
     * Returns error message by code.
     *
     * @param string $errorCode
     *
     * @return string
     */
    abstract protected function getErrorMessageByCode($errorCode);
}
