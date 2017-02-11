<?php
namespace Virgil\Sdk\Client\VirgilCards\Mapper;


use Virgil\Sdk\Client\AbstractJsonModelMapper;

use Virgil\Sdk\Client\VirgilCards\Model\CardContentModel;
use Virgil\Sdk\Client\VirgilCards\Model\DeviceInfoModel;

/**
 * Class transforms card content model to json and vise versa.
 */
class CardContentModelMapper extends AbstractJsonModelMapper
{
    const PUBLIC_KEY_ATTRIBUTE_NAME = 'public_key';
    const DATA_ATTRIBUTE_NAME = 'data';
    const INFO_ATTRIBUTE_NAME = 'info';
    const INFO_DEVICE_ATTRIBUTE_NAME = 'device';
    const INFO_DEVICE_NAME_ATTRIBUTE_NAME = 'device_name';


    /**
     * @inheritdoc
     *
     * @return CardContentModel
     */
    public function toModel($json)
    {
        $cardContentData = json_decode($json, true);

        $cardContentModelArguments = [
            $cardContentData[self::IDENTITY_ATTRIBUTE_NAME],
            $cardContentData[self::IDENTITY_TYPE_ATTRIBUTE_NAME],
            $cardContentData[self::PUBLIC_KEY_ATTRIBUTE_NAME],
            $cardContentData[self::SCOPE_ATTRIBUTE_NAME],
        ];

        if (array_key_exists(self::DATA_ATTRIBUTE_NAME, $cardContentData)) {
            $cardContentModelArguments[] = $cardContentData[self::DATA_ATTRIBUTE_NAME];
        } else {
            $cardContentModelArguments[] = [];
        }

        if (array_key_exists(self::INFO_ATTRIBUTE_NAME, $cardContentData)) {
            $deviceInfoModelArguments = [];

            $cardInfo = $cardContentData[self::INFO_ATTRIBUTE_NAME];

            if (array_key_exists(self::INFO_DEVICE_ATTRIBUTE_NAME, $cardInfo)) {
                $deviceInfoModelArguments[] = $cardInfo[self::INFO_DEVICE_ATTRIBUTE_NAME];
            }

            if (array_key_exists(self::INFO_DEVICE_NAME_ATTRIBUTE_NAME, $cardInfo)) {
                $deviceInfoModelArguments[] = $cardInfo[self::INFO_DEVICE_NAME_ATTRIBUTE_NAME];
            }

            $cardContentModelArguments[] = new DeviceInfoModel(...$deviceInfoModelArguments);
        }

        return new CardContentModel(...$cardContentModelArguments);
    }
}
