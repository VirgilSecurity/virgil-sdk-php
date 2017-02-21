<?php
namespace Virgil\Sdk\Tests\Unit\Client\VirgilServices\Model;


use Virgil\Sdk\Client\VirgilServices\Model\CardContentModel;
use Virgil\Sdk\Client\VirgilServices\Model\DeviceInfoModel;
use Virgil\Sdk\Client\VirgilServices\Model\RevokeCardContentModel;
use Virgil\Sdk\Client\VirgilServices\Model\SignedRequestMetaModel;
use Virgil\Sdk\Client\VirgilServices\Model\SignedRequestModel;
use Virgil\Sdk\Client\VirgilServices\Model\ValidationModel;

class RequestModel
{
    public static function createDeviceInfoContentRequestModel($cardContentData, $cardSigns)
    {
        return new SignedRequestModel(
            new DeviceInfoModel(...$cardContentData), static::createSignedRequestMetaModel($cardSigns)
        );
    }


    public static function createCreateCardRequestModel($contentData, $cardMeta)
    {
        return new SignedRequestModel(
            new CardContentModel(...$contentData), static::createSignedRequestMetaModel(...$cardMeta)
        );
    }


    public static function createRevokeCardRequestModel($contentData, $cardMeta)
    {
        return new SignedRequestModel(
            new RevokeCardContentModel(...$contentData), static::createSignedRequestMetaModel(...$cardMeta)
        );
    }


    public static function createSignedRequestMetaModel($cardSigns, $cardValidationToken = null)
    {
        $cardValidation = $cardValidationToken !== null ? new ValidationModel($cardValidationToken) : null;

        return new SignedRequestMetaModel($cardSigns, $cardValidation);
    }
}
