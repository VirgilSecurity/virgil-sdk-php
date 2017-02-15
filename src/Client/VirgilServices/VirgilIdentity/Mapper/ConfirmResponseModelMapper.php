<?php
namespace Virgil\Sdk\Client\VirgilServices\VirgilIdentity\Mapper;


use Virgil\Sdk\Client\VirgilServices\Mapper\AbstractJsonModelMapper;

use Virgil\Sdk\Client\VirgilServices\VirgilIdentity\Constants\JsonProperties;

use Virgil\Sdk\Client\VirgilServices\VirgilIdentity\Model\ConfirmResponseModel;

/**
 * Class transforms confirm response model to json and vise versa.
 */
class ConfirmResponseModelMapper extends AbstractJsonModelMapper
{
    /**
     * @inheritdoc
     *
     * @return ConfirmResponseModel
     */
    public function toModel($json)
    {
        $data = json_decode($json, true);

        return new ConfirmResponseModel(
            $data[JsonProperties::TYPE_ATTRIBUTE_NAME],
            $data[JsonProperties::VALUE_ATTRIBUTE_NAME],
            $data[JsonProperties::VALIDATION_TOKEN_ATTRIBUTE_NAME]
        );
    }
}
