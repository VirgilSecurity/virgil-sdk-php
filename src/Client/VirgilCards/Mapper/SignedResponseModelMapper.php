<?php
namespace Virgil\Sdk\Client\VirgilCards\Mapper;


use DateTime;

use Virgil\Sdk\Client\AbstractJsonModelMapper;

use Virgil\Sdk\Client\VirgilCards\Model\CardContentModel;
use Virgil\Sdk\Client\VirgilCards\Model\DeviceInfoModel;
use Virgil\Sdk\Client\VirgilCards\Model\SignedResponseMetaModel;
use Virgil\Sdk\Client\VirgilCards\Model\SignedResponseModel;

/**
 * Class transforms signed response json to model.
 */
class SignedResponseModelMapper extends AbstractJsonModelMapper
{
    const PUBLIC_KEY_ATTRIBUTE_NAME = 'public_key';
    const DATA_ATTRIBUTE_NAME = 'data';
    const INFO_ATTRIBUTE_NAME = 'info';
    const INFO_DEVICE_ATTRIBUTE_NAME = 'device';
    const INFO_DEVICE_NAME_ATTRIBUTE_NAME = 'device_name';
    const ID_ATTRIBUTE_NAME = 'id';
    const CARD_VERSION_ATTRIBUTE_NAME = 'card_version';
    const CREATED_AT_ATTRIBUTE_NAME = 'created_at';


    /**
     * @inheritdoc
     *
     * @return SignedResponseModel
     */
    public function toModel($json)
    {
        $data = json_decode($json, true);
        $cardContentData = json_decode(base64_decode($data[self::CONTENT_SNAPSHOT_ATTRIBUTE_NAME]), true);
        $cardMetaData = $data[self::META_ATTRIBUTE_NAME];

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

        $cardContentModel = new CardContentModel(...$cardContentModelArguments);

        $cardMetaModel = new SignedResponseMetaModel(
            $cardMetaData[self::SIGNS_ATTRIBUTE_NAME],
            new DateTime($cardMetaData[self::CREATED_AT_ATTRIBUTE_NAME]),
            $cardMetaData[self::CARD_VERSION_ATTRIBUTE_NAME]
        );

        return new SignedResponseModel(
            $data[self::ID_ATTRIBUTE_NAME],
            $data[self::CONTENT_SNAPSHOT_ATTRIBUTE_NAME],
            $cardContentModel,
            $cardMetaModel
        );
    }
}
