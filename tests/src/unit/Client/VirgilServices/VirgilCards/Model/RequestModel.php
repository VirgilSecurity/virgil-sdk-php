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


    public static function createCreateCardRequestModel($contentData, $cardSigns, $contentSnapshot = null)
    {
        return parent::createCreateCardRequestModel($contentData, [$cardSigns], $contentSnapshot);
    }


    public static function createRevokeCardRequestModel($contentData, $cardSigns, $contentSnapshot = null)
    {
        return parent::createRevokeCardRequestModel($contentData, [$cardSigns], $contentSnapshot);
    }
}
