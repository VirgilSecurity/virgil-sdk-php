<?php
namespace Virgil\Sdk\Tests\Unit\Client\VirgilServices\VirgilCards\Model;


use Virgil\Sdk\Client\VirgilServices\VirgilCards\Model\CardContentModel;
use Virgil\Sdk\Client\VirgilServices\VirgilCards\Model\DeviceInfoModel;
use Virgil\Sdk\Client\VirgilServices\VirgilCards\Model\RevokeCardContentModel;
use Virgil\Sdk\Client\VirgilServices\VirgilCards\Model\SearchRequestModel;
use Virgil\Sdk\Client\VirgilServices\VirgilCards\Model\SignedRequestMetaModel;
use Virgil\Sdk\Client\VirgilServices\VirgilCards\Model\SignedRequestModel;

class RequestModel
{
    public static function createDeviceInfoContentRequestModel($cardContentData, $cardSigns)
    {
        return new SignedRequestModel(
            new DeviceInfoModel(...$cardContentData), new SignedRequestMetaModel($cardSigns)
        );
    }


    public static function createCreateCardRequestModel($contentData, $cardSigns)
    {
        return new SignedRequestModel(
            new CardContentModel(...$contentData), new SignedRequestMetaModel($cardSigns)
        );
    }


    public static function createRevokeCardRequestModel($contentData, $cardSigns)
    {
        return new SignedRequestModel(
            new RevokeCardContentModel(...$contentData), new SignedRequestMetaModel($cardSigns)
        );
    }


    public static function createSearchRequestModel($identities, $identityType = null, $scope = null)
    {
        return new SearchRequestModel($identities, $identityType, $scope);
    }
}
