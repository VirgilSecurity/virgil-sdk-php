<?php
namespace Virgil\Sdk\Client\VirgilServices\Mapper;


use Virgil\Sdk\Client\VirgilServices\Constants\JsonProperties;

use Virgil\Sdk\Client\VirgilServices\Model\CardContentModel;
use Virgil\Sdk\Client\VirgilServices\Model\DeviceInfoModel;

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

        $cardContentModelArguments[] = $this->getPropertyValue(
            $cardContentData[JsonProperties::DATA_ATTRIBUTE_NAME],
            []
        );

        $cardInfo = $this->getPropertyValue($cardContentData[JsonProperties::INFO_ATTRIBUTE_NAME], []);

        $deviceInfoModelArguments[] = $this->getPropertyValue($cardInfo[JsonProperties::INFO_DEVICE_ATTRIBUTE_NAME]);
        $deviceInfoModelArguments[] = $this->getPropertyValue(
            $cardInfo[JsonProperties::INFO_DEVICE_NAME_ATTRIBUTE_NAME]
        );

        $cardContentModelArguments[] = new DeviceInfoModel(...$deviceInfoModelArguments);

        return new CardContentModel(...$cardContentModelArguments);
    }
}
