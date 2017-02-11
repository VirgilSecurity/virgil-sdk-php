<?php
namespace Virgil\Sdk\Tests\Unit\Client\VirgilCards\Mapper;


use Virgil\Sdk\Client\VirgilCards\Mapper\CardContentModelMapper;
use Virgil\Sdk\Client\VirgilCards\Mapper\ErrorResponseModelMapper;
use Virgil\Sdk\Client\VirgilCards\Mapper\ModelMappersCollection;
use Virgil\Sdk\Client\VirgilCards\Mapper\SearchRequestModelMapper;
use Virgil\Sdk\Client\VirgilCards\Mapper\SignedResponseModelsMapper;
use Virgil\Sdk\Client\VirgilCards\Mapper\SignedRequestModelMapper;
use Virgil\Sdk\Client\VirgilCards\Mapper\SignedResponseModelMapper;

class MappersCollection
{
    public static function getMappers()
    {
        $signedResponseModelMapper = new SignedResponseModelMapper(new CardContentModelMapper());

        return new ModelMappersCollection(
            $signedResponseModelMapper,
            new SignedRequestModelMapper(),
            new SignedResponseModelsMapper($signedResponseModelMapper),
            new SearchRequestModelMapper(),
            new ErrorResponseModelMapper()
        );
    }
}
