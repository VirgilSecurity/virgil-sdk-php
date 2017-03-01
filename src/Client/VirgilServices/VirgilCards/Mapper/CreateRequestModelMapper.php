<?php
namespace Virgil\Sdk\Client\VirgilServices\VirgilCards\Mapper;


use Virgil\Sdk\Client\VirgilServices\Mapper\CardContentModelMapper;
use Virgil\Sdk\Client\VirgilServices\Mapper\SignedRequestModelMapper;

use Virgil\Sdk\Client\VirgilServices\Model\SignedRequestMetaModel;
use Virgil\Sdk\Client\VirgilServices\Model\SignedRequestModel;
use Virgil\Sdk\Client\VirgilServices\Model\ValidationModel;

use Virgil\Sdk\Client\VirgilServices\Constants\JsonProperties;

/**
 * Class transforms create request model to json and vise versa.
 */
class CreateRequestModelMapper extends SignedRequestModelMapper
{
    /** @var CardContentModelMapper $cardContentModelMapper */
    private $cardContentModelMapper;


    /**
     * Class constructor.
     *
     * @param CardContentModelMapper $cardContentModelMapper
     */
    public function __construct(CardContentModelMapper $cardContentModelMapper)
    {
        $this->cardContentModelMapper = $cardContentModelMapper;
    }


    /**
     * @inheritdoc
     *
     * @return SignedRequestModel
     */
    public function toModel($json)
    {
        $data = json_decode($json, true);

        $contentSnapshot = $data[JsonProperties::CONTENT_SNAPSHOT_ATTRIBUTE_NAME];
        $cardContentJson = base64_decode($contentSnapshot);
        $cardMetaData = $data[JsonProperties::META_ATTRIBUTE_NAME];

        $cardContentModel = $this->cardContentModelMapper->toModel($cardContentJson);

        $requestMeta[] = (array)$cardMetaData[JsonProperties::SIGNS_ATTRIBUTE_NAME];

        if (array_key_exists(JsonProperties::VALIDATION_ATTRIBUTE_NAME, $cardMetaData)) {

            $cardValidationMetaData = $cardMetaData[JsonProperties::VALIDATION_ATTRIBUTE_NAME];
            if (array_key_exists(JsonProperties::TOKEN_ATTRIBUTE_NAME, $cardValidationMetaData)) {
                $requestMeta[] = new ValidationModel($cardValidationMetaData[JsonProperties::TOKEN_ATTRIBUTE_NAME]);
            }
        }

        $cardMetaModel = new SignedRequestMetaModel(...$requestMeta);

        return new SignedRequestModel($cardContentModel, $cardMetaModel, $contentSnapshot);
    }
}
