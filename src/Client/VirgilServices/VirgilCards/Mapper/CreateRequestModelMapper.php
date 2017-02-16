<?php
namespace Virgil\Sdk\Client\VirgilServices\VirgilCards\Mapper;


use Virgil\Sdk\Client\VirgilServices\Mapper\CardContentModelMapper;
use Virgil\Sdk\Client\VirgilServices\Mapper\SignedRequestModelMapper;

use Virgil\Sdk\Client\VirgilServices\Model\SignedRequestMetaModel;
use Virgil\Sdk\Client\VirgilServices\Model\SignedRequestModel;

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
        $cardContentJson = base64_decode($data[JsonProperties::CONTENT_SNAPSHOT_ATTRIBUTE_NAME]);
        $cardMetaData = $data[JsonProperties::META_ATTRIBUTE_NAME];

        $cardContentModel = $this->cardContentModelMapper->toModel($cardContentJson);

        $cardMetaModel = new SignedRequestMetaModel($cardMetaData[JsonProperties::SIGNS_ATTRIBUTE_NAME]);

        return new SignedRequestModel($cardContentModel, $cardMetaModel);
    }
}
