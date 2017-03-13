<?php
namespace Virgil\Sdk\Client\VirgilServices\Mapper;


use DateTime;

use Virgil\Sdk\Client\VirgilServices\Constants\JsonProperties;

use Virgil\Sdk\Client\VirgilServices\Model\SignedResponseMetaModel;
use Virgil\Sdk\Client\VirgilServices\Model\SignedResponseModel;

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
        $cardMetaData = $this->getPropertyValue($data[JsonProperties::META_ATTRIBUTE_NAME], []);

        $cardContentModel = $this->cardContentModelMapper->toModel($cardContentJson);

        $cardMetaModel = new SignedResponseMetaModel(
            $this->getPropertyValue($cardMetaData[JsonProperties::SIGNS_ATTRIBUTE_NAME], []),
            new DateTime($this->getPropertyValue($cardMetaData[JsonProperties::CREATED_AT_ATTRIBUTE_NAME])),
            $this->getPropertyValue($cardMetaData[JsonProperties::CARD_VERSION_ATTRIBUTE_NAME])
        );

        return new SignedResponseModel(
            $data[JsonProperties::ID_ATTRIBUTE_NAME],
            $data[JsonProperties::CONTENT_SNAPSHOT_ATTRIBUTE_NAME],
            $cardContentModel,
            $cardMetaModel
        );
    }
}
