<?php
namespace Virgil\Sdk\Tests\Unit\Client\VirgilServices\Mapper;


use Virgil\Sdk\Client\VirgilServices\Mapper\AbstractErrorResponseModelMapper;

class ErrorResponseModelMapper extends AbstractErrorResponseModelMapper
{
    /**
     * @inheritdoc
     */
    protected function getErrorMessageByCode($errorCode)
    {
        return '123 error message';
    }
}
