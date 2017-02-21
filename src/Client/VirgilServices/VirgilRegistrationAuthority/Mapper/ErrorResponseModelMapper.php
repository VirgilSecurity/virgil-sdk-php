<?php
namespace Virgil\Sdk\Client\VirgilServices\VirgilRegistrationAuthority\Mapper;


use Virgil\Sdk\Client\VirgilServices\Mapper\AbstractErrorResponseModelMapper;

use Virgil\Sdk\Client\VirgilServices\VirgilRegistrationAuthority\RegistrationAuthorityErrorMessages;

/**
 * Class transforms Virgil Registration Authority Service error response message from json string to model
 * representation and vise versa.
 */
class ErrorResponseModelMapper extends AbstractErrorResponseModelMapper
{
    /**
     * @inheritdoc
     */
    protected function getErrorMessageByCode($errorCode)
    {
        return RegistrationAuthorityErrorMessages::getMessage($errorCode);
    }
}
