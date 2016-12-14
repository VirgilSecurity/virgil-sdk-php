<?php
namespace Virgil\Sdk\Client\VirgilCards\Mapper;


use Virgil\Sdk\Client\VirgilCards\Model\CardContentModel;
use Virgil\Sdk\Client\VirgilCards\Model\DeviceInfoModel;
use Virgil\Sdk\Client\VirgilCards\Model\SignedRequestMetaModel;
use Virgil\Sdk\Client\VirgilCards\Model\SignedRequestModel;

/**
 * Class transforms create request model to json and vise versa.
 */
class CreateRequestModelMapper extends AbstractJsonModelMapper
{
    /** @var SignedRequestModelMapper $signedRequestModelMapper */
    private $signedRequestModelMapper;


    /**
     * Class constructor.
     *
     * @param SignedRequestModelMapper $signedRequestModelMapper
     */
    public function __construct(SignedRequestModelMapper $signedRequestModelMapper)
    {
        $this->signedRequestModelMapper = $signedRequestModelMapper;
    }


    /**
     * @inheritdoc
     *
     * @return SignedRequestModel
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

        $cardMetaModel = new SignedRequestMetaModel($cardMetaData['signs']);

        return new SignedRequestModel($cardContentModel, $cardMetaModel);
    }


    /**
     * @inheritdoc
     */
    public function toJson($model)
    {
        return $this->signedRequestModelMapper->toJson($model);
    }
}
