<?php
namespace Virgil\Sdk\Client\VirgilServices\VirgilCards\Mapper;


use Virgil\Sdk\Client\VirgilServices\Mapper\AbstractErrorResponseModelMapper;

use Virgil\Sdk\Client\VirgilServices\VirgilCards\CardsErrorMessages;

/**
 * Class transforms virgil cards service error response message from json string to model representation and vise versa.
 */
class ErrorResponseModelMapper extends AbstractErrorResponseModelMapper
{
    /**
     * @inheritdoc
     */
    protected function getErrorMessageByCode($errorCode)
    {
        return CardsErrorMessages::getMessage($errorCode);
    }
}
