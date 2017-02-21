<?php
namespace Virgil\Sdk\Tests\Unit\Client\VirgilServices\Model;


use Virgil\Sdk\Client\VirgilServices\Model\CardContentModel;
use Virgil\Sdk\Client\VirgilServices\Model\ErrorResponseModel;
use Virgil\Sdk\Client\VirgilServices\Model\SignedResponseMetaModel;
use Virgil\Sdk\Client\VirgilServices\Model\SignedResponseModel;

class ResponseModel
{
    public static function createErrorResponseModel($code, $message)
    {
        return new ErrorResponseModel($code, $message);
    }

    public static function createSignedResponseModel($id, $contentSnapshot, $contentData, $metaData)
    {
        return new SignedResponseModel(
            $id, $contentSnapshot, new CardContentModel(...$contentData), new SignedResponseMetaModel(...$metaData)
        );
    }
}
