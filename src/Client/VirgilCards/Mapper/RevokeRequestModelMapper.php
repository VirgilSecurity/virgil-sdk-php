<?php
namespace Virgil\Sdk\Client\VirgilCards\Mapper;


use Virgil\Sdk\Client\VirgilCards\Constants\JsonProperties;

use Virgil\Sdk\Client\VirgilCards\Model\RevokeCardContentModel;
use Virgil\Sdk\Client\VirgilCards\Model\SignedRequestMetaModel;
use Virgil\Sdk\Client\VirgilCards\Model\SignedRequestModel;

/**
 * Class transforms revocation request model to json and vise versa.
 */
class RevokeRequestModelMapper extends SignedRequestModelMapper
{
    /**
     * @inheritdoc
     *
     * @return SignedRequestModel
     */
    public function toModel($json)
    {
        $data = json_decode($json, true);
        $cardContentData = json_decode(base64_decode($data[JsonProperties::CONTENT_SNAPSHOT_ATTRIBUTE_NAME]), true);
        $cardMetaData = $data[JsonProperties::META_ATTRIBUTE_NAME];

        $cardContentModel = new RevokeCardContentModel(
            $cardContentData[JsonProperties::CARD_ID_ATTRIBUTE_NAME],
            $cardContentData[JsonProperties::REVOCATION_REASON_ATTRIBUTE_NAME]
        );

        $cardMetaModel = new SignedRequestMetaModel($cardMetaData[JsonProperties::SIGNS_ATTRIBUTE_NAME]);

        return new SignedRequestModel($cardContentModel, $cardMetaModel);
    }
}
