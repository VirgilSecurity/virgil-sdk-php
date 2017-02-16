<?php
namespace Virgil\Sdk\Tests\Unit\Client\VirgilServices\VirgilCards\Model;


use Virgil\Sdk\Tests\Unit\Client\VirgilServices\Model\RequestModel as CommonRequestModel;

use Virgil\Sdk\Client\VirgilServices\VirgilCards\Model\SearchRequestModel;

class RequestModel extends CommonRequestModel
{
    public static function createSearchRequestModel($identities, $identityType = null, $scope = null)
    {
        return new SearchRequestModel($identities, $identityType, $scope);
    }


    public static function createCreateCardRequestModel($contentData, $cardSigns)
    {
        return parent::createCreateCardRequestModel($contentData, [$cardSigns]);
    }


    public static function createRevokeCardRequestModel($contentData, $cardSigns)
    {
        return parent::createRevokeCardRequestModel($contentData, [$cardSigns]);
    }
}
