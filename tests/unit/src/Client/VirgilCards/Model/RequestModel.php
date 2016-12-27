<?php
namespace Virgil\Sdk\Tests\Unit\Client\VirgilCards\Model;


use Virgil\Sdk\Client\VirgilCards\Model\CardContentModel;
use Virgil\Sdk\Client\VirgilCards\Model\DeviceInfoModel;
use Virgil\Sdk\Client\VirgilCards\Model\RevokeCardContentModel;
use Virgil\Sdk\Client\VirgilCards\Model\SignedRequestMetaModel;
use Virgil\Sdk\Client\VirgilCards\Model\SignedRequestModel;

use Virgil\Sdk\Client\VirgilCards\SearchCriteria;

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


    public static function createSearchCriteria($identities, $identityType = null, $scope = null)
    {
        return new SearchCriteria($identities, $identityType, $scope);
    }
}
