<?php
namespace Virgil\Sdk\Tests\Unit\Client\VirgilCards\Model;


use Virgil\Sdk\Client\VirgilCards\Model\CardContentModel;
use Virgil\Sdk\Client\VirgilCards\Model\ErrorResponseModel;
use Virgil\Sdk\Client\VirgilCards\Model\SignedResponseMetaModel;
use Virgil\Sdk\Client\VirgilCards\Model\SignedResponseModel;

class ResponseModel
{
    public static function createSignedResponseModel($id, $contentSnapshot, $contentData, $metaData)
    {
        return new SignedResponseModel(
            $id, $contentSnapshot, new CardContentModel(...$contentData), new SignedResponseMetaModel(...$metaData)
        );
    }


    public static function createSignedResponseModels($signedResponseModelsArgs)
    {
        $expectedCardsServiceResponseArgsToModels = function ($cardServiceResponseArgs) {
            return ResponseModel::createSignedResponseModel(...$cardServiceResponseArgs);
        };

        return array_map(
            $expectedCardsServiceResponseArgsToModels,
            $signedResponseModelsArgs
        );
    }


    public static function createErrorResponseModel($code)
    {
        return new ErrorResponseModel($code);
    }
}
