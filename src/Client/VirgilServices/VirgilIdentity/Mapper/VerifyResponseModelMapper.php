<?php
namespace Virgil\Sdk\Client\VirgilServices\VirgilIdentity\Mapper;


use Virgil\Sdk\Client\VirgilServices\VirgilIdentity\Constants\JsonProperties;

use Virgil\Sdk\Client\VirgilServices\VirgilIdentity\Model\VerifyResponseModel;

use Virgil\Sdk\Client\VirgilServices\Mapper\AbstractJsonModelMapper;

/**
 * Class transforms verify response model to model.
 */
class VerifyResponseModelMapper extends AbstractJsonModelMapper
{
    /**
     * @inheritdoc
     *
     * @return VerifyResponseModel
     */
    public function toModel($json)
    {
        $data = json_decode($json, true);

        return new VerifyResponseModel($data[JsonProperties::ACTION_ID_ATTRIBUTE_NAME]);
    }
}
