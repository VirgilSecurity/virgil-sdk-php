<?php
namespace Virgil\Sdk\Client\VirgilCards\Mapper;


use DateTime;

use Virgil\Sdk\Client\VirgilCards\Constants\JsonProperties;
use Virgil\Sdk\Client\VirgilCards\Model\SignedResponseMetaModel;
use Virgil\Sdk\Client\VirgilCards\Model\SignedResponseModel;

use Virgil\Sdk\Client\VirgilServices\Mapper\AbstractJsonModelMapper;

/**
 * Class transforms signed response json to model.
 */
class SignedResponseModelMapper extends AbstractJsonModelMapper
{
    /** @var CardContentModelMapper */
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
     * @return SignedResponseModel
     */
    public function toModel($json)
    {
        $data = json_decode($json, true);
        $cardContentJson = base64_decode($data[JsonProperties::CONTENT_SNAPSHOT_ATTRIBUTE_NAME]);
        $cardMetaData = $data[JsonProperties::META_ATTRIBUTE_NAME];

        $cardContentModel = $this->cardContentModelMapper->toModel($cardContentJson);

        $cardMetaModel = new SignedResponseMetaModel(
            $cardMetaData[JsonProperties::SIGNS_ATTRIBUTE_NAME],
            new DateTime($cardMetaData[JsonProperties::CREATED_AT_ATTRIBUTE_NAME]),
            $cardMetaData[JsonProperties::CARD_VERSION_ATTRIBUTE_NAME]
        );

        return new SignedResponseModel(
            $data[JsonProperties::ID_ATTRIBUTE_NAME],
            $data[JsonProperties::CONTENT_SNAPSHOT_ATTRIBUTE_NAME],
            $cardContentModel,
            $cardMetaModel
        );
    }
}
