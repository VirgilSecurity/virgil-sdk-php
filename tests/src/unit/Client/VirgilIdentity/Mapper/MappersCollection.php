<?php
namespace Virgil\Sdk\Tests\Unit\Client\VirgilIdentity\Mapper;


use Virgil\Sdk\Client\VirgilCards\Mapper\ErrorResponseModelMapper;

use Virgil\Sdk\Client\VirgilIdentity\Mapper\ConfirmRequestModelMapper;
use Virgil\Sdk\Client\VirgilIdentity\Mapper\ConfirmResponseModelMapper;
use Virgil\Sdk\Client\VirgilIdentity\Mapper\ModelMappersCollection;
use Virgil\Sdk\Client\VirgilIdentity\Mapper\ValidateRequestModelMapper;
use Virgil\Sdk\Client\VirgilIdentity\Mapper\VerifyRequestModelMapper;
use Virgil\Sdk\Client\VirgilIdentity\Mapper\VerifyResponseModelMapper;

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
