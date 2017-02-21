<?php
namespace Virgil\Sdk\Tests\Unit\Client\VirgilServices\VirgilIdentity\Mapper;


use Virgil\Sdk\Client\VirgilServices\VirgilIdentity\Mapper\ConfirmRequestModelMapper;
use Virgil\Sdk\Client\VirgilServices\VirgilIdentity\Mapper\ConfirmResponseModelMapper;
use Virgil\Sdk\Client\VirgilServices\VirgilIdentity\Mapper\ErrorResponseModelMapper;
use Virgil\Sdk\Client\VirgilServices\VirgilIdentity\Mapper\ModelMappersCollection;
use Virgil\Sdk\Client\VirgilServices\VirgilIdentity\Mapper\ValidateRequestModelMapper;
use Virgil\Sdk\Client\VirgilServices\VirgilIdentity\Mapper\VerifyRequestModelMapper;
use Virgil\Sdk\Client\VirgilServices\VirgilIdentity\Mapper\VerifyResponseModelMapper;

class MappersCollection
{
    public static function getMappers()
    {
        return new ModelMappersCollection(
            new VerifyRequestModelMapper(),
            new VerifyResponseModelMapper(),
            new ConfirmRequestModelMapper(),
            new ConfirmResponseModelMapper(),
            new ValidateRequestModelMapper(),
            new ErrorResponseModelMapper()
        );
    }
}
