<?php
namespace Virgil\Sdk\Client\VirgilServices\VirgilCards\Mapper;


use Virgil\Sdk\Client\VirgilServices\Mapper\SignedRequestModelMapper;

use Virgil\Sdk\Client\VirgilServices\Model\RevokeCardContentModel;
use Virgil\Sdk\Client\VirgilServices\Model\SignedRequestMetaModel;
use Virgil\Sdk\Client\VirgilServices\Model\SignedRequestModel;
use Virgil\Sdk\Client\VirgilServices\Model\ValidationModel;

use Virgil\Sdk\Client\VirgilServices\Constants\JsonProperties;

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

        $contentSnapshot = $data[JsonProperties::CONTENT_SNAPSHOT_ATTRIBUTE_NAME];
        $cardContentData = json_decode(base64_decode($contentSnapshot), true);
        $cardMetaData = $data[JsonProperties::META_ATTRIBUTE_NAME];

        $cardContentModel = new RevokeCardContentModel(
            $cardContentData[JsonProperties::CARD_ID_ATTRIBUTE_NAME],
            $cardContentData[JsonProperties::REVOCATION_REASON_ATTRIBUTE_NAME]
        );

        $requestMeta[] = (array)$cardMetaData[JsonProperties::SIGNS_ATTRIBUTE_NAME];

        $cardValidationMetaData = $this->getPropertyValue($cardMetaData[JsonProperties::VALIDATION_ATTRIBUTE_NAME], []);

        if (array_key_exists(JsonProperties::TOKEN_ATTRIBUTE_NAME, $cardValidationMetaData)) {
            $requestMeta[] = new ValidationModel($cardValidationMetaData[JsonProperties::TOKEN_ATTRIBUTE_NAME]);
        }

        $cardMetaModel = new SignedRequestMetaModel(...$requestMeta);

        return new SignedRequestModel($cardContentModel, $cardMetaModel, $contentSnapshot);
    }
}
