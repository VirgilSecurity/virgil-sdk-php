<?php
namespace Virgil\Sdk\Tests\Unit\Client\VirgilServices\VirgilCards\Model;


use Virgil\Sdk\Tests\Unit\Client\VirgilServices\Model\ResponseModel as CommonResponseModel;

class ResponseModel extends CommonResponseModel
{
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
