<?php
namespace Virgil\Sdk\Tests\Unit\Client\VirgilServices\VirgilCards\Model;


use Virgil\Sdk\Client\VirgilServices\VirgilCards\Model\CardContentModel;
use Virgil\Sdk\Client\VirgilServices\VirgilCards\Model\SignedResponseMetaModel;
use Virgil\Sdk\Client\VirgilServices\VirgilCards\Model\SignedResponseModel;

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
}
