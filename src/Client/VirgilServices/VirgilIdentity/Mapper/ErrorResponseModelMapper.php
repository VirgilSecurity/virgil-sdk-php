<?php
namespace Virgil\Sdk\Client\VirgilServices\VirgilIdentity\Mapper;


use Virgil\Sdk\Client\VirgilServices\Mapper\AbstractErrorResponseModelMapper;

use Virgil\Sdk\Client\VirgilServices\VirgilIdentity\IdentityErrorMessages;

/**
 * Class transforms error response message json string to model.
 */
class ErrorResponseModelMapper extends AbstractErrorResponseModelMapper
{
    /**
     * @inheritdoc
     */
    protected function getErrorMessageByCode($errorCode)
    {
        return IdentityErrorMessages::getMessage($errorCode);
    }
}
