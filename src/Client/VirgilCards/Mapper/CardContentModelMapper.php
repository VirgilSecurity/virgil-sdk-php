<?php
namespace Virgil\Sdk\Client\VirgilCards\Mapper;


use Virgil\Sdk\Client\VirgilCards\Constants\JsonProperties;

use Virgil\Sdk\Client\VirgilCards\Model\CardContentModel;
use Virgil\Sdk\Client\VirgilCards\Model\DeviceInfoModel;

use Virgil\Sdk\Client\VirgilServices\Mapper\AbstractJsonModelMapper;

/**
 * Class transforms card content model to json and vise versa.
 */
class CardContentModelMapper extends AbstractJsonModelMapper
{
    /**
     * @inheritdoc
     *
     * @return CardContentModel
     */
    public function toModel($json)
    {
        $cardContentData = json_decode($json, true);

        $cardContentModelArguments = [
            $cardContentData[JsonProperties::IDENTITY_ATTRIBUTE_NAME],
            $cardContentData[JsonProperties::IDENTITY_TYPE_ATTRIBUTE_NAME],
            $cardContentData[JsonProperties::PUBLIC_KEY_ATTRIBUTE_NAME],
            $cardContentData[JsonProperties::SCOPE_ATTRIBUTE_NAME],
        ];

        if (array_key_exists(JsonProperties::DATA_ATTRIBUTE_NAME, $cardContentData)) {
            $cardContentModelArguments[] = $cardContentData[JsonProperties::DATA_ATTRIBUTE_NAME];
        } else {
            $cardContentModelArguments[] = [];
        }

        if (array_key_exists(JsonProperties::INFO_ATTRIBUTE_NAME, $cardContentData)) {
            $deviceInfoModelArguments = [];

            $cardInfo = $cardContentData[JsonProperties::INFO_ATTRIBUTE_NAME];

            if (array_key_exists(JsonProperties::INFO_DEVICE_ATTRIBUTE_NAME, $cardInfo)) {
                $deviceInfoModelArguments[] = $cardInfo[JsonProperties::INFO_DEVICE_ATTRIBUTE_NAME];
            }

            if (array_key_exists(JsonProperties::INFO_DEVICE_NAME_ATTRIBUTE_NAME, $cardInfo)) {
                $deviceInfoModelArguments[] = $cardInfo[JsonProperties::INFO_DEVICE_NAME_ATTRIBUTE_NAME];
            }

            $cardContentModelArguments[] = new DeviceInfoModel(...$deviceInfoModelArguments);
        }

        return new CardContentModel(...$cardContentModelArguments);
    }
}
