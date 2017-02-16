<?php
namespace Virgil\Sdk\Tests\Unit\Client\VirgilServices\RegistrationAuthority\Mapper;


use Virgil\Sdk\Client\VirgilServices\Mapper\CardContentModelMapper;
use Virgil\Sdk\Client\VirgilServices\Mapper\SignedRequestModelMapper;
use Virgil\Sdk\Client\VirgilServices\Mapper\SignedResponseModelMapper;

use Virgil\Sdk\Client\VirgilServices\VirgilRegistrationAuthority\Mapper\ErrorResponseModelMapper;
use Virgil\Sdk\Client\VirgilServices\VirgilRegistrationAuthority\Mapper\ModelMappersCollection;

class MappersCollection
{
    public static function getMappers()
    {
        return new ModelMappersCollection(
            new SignedResponseModelMapper(new CardContentModelMapper()),
            new SignedRequestModelMapper(),
            new ErrorResponseModelMapper()
        );
    }
}
