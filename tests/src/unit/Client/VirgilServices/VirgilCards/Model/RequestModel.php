<?php
namespace Virgil\Sdk\Tests\Unit\Client\VirgilServices\VirgilCards\Model;


use Virgil\Sdk\Client\VirgilServices\Model\CardContentModel;
use Virgil\Sdk\Client\VirgilServices\Model\DeviceInfoModel;
use Virgil\Sdk\Client\VirgilServices\Model\RevokeCardContentModel;
use Virgil\Sdk\Client\VirgilServices\Model\SignedRequestMetaModel;
use Virgil\Sdk\Client\VirgilServices\Model\SignedRequestModel;

use Virgil\Sdk\Client\VirgilServices\VirgilCards\Model\SearchRequestModel;

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
