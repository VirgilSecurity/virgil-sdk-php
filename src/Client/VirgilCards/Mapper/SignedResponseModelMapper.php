<?php
namespace Virgil\Sdk\Client\VirgilCards\Mapper;


use DateTime;

use Virgil\Sdk\Client\VirgilCards\Model\CardContentModel;
use Virgil\Sdk\Client\VirgilCards\Model\DeviceInfoModel;
use Virgil\Sdk\Client\VirgilCards\Model\SignedResponseMetaModel;
use Virgil\Sdk\Client\VirgilCards\Model\SignedResponseModel;

/**
 * Class transforms signed response model to model.
 */
class SignedResponseModelMapper extends AbstractJsonModelMapper
{
    /**
     * @inheritdoc
     *
     * @return SignedResponseModel
     */
    public function toModel($json)
    {
        $data = json_decode($json, true);
        $cardContentData = json_decode(base64_decode($data['content_snapshot']), true);
        $cardMetaData = $data['meta'];

        $cardContentModelArguments = [
            $cardContentData['identity'],
            $cardContentData['identity_type'],
            $cardContentData['public_key'],
            $cardContentData['scope'],
        ];

        if (array_key_exists('data', $cardContentData)) {
            $cardContentModelArguments[] = $cardContentData['data'];
        } else {
            $cardContentModelArguments[] = [];
        }

        if (array_key_exists('info', $cardContentData)) {
            $deviceInfoModelArguments = [];

            $cardInfo = $cardContentData['info'];

            if (array_key_exists('device', $cardInfo)) {
                $deviceInfoModelArguments[] = $cardInfo['device'];
            }

            if (array_key_exists('device_name', $cardInfo)) {
                $deviceInfoModelArguments[] = $cardInfo['device_name'];
            }

            $cardContentModelArguments[] = new DeviceInfoModel(...$deviceInfoModelArguments);
        }

        $cardContentModel = new CardContentModel(...$cardContentModelArguments);

        $cardMetaModel = new SignedResponseMetaModel(
            $cardMetaData['signs'],
            new DateTime($cardMetaData['created_at']),
            $cardMetaData['card_version']
        );

        return new SignedResponseModel($data['id'], $data['content_snapshot'], $cardContentModel, $cardMetaModel);
    }
}
